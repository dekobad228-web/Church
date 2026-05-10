<?php
namespace App\Schedule;
use App\Helpers;

class CalendarAjax
{
    public static function register(): void
    {
        add_action('wp_ajax_calendar',        [self::class, 'handle']);
        add_action('wp_ajax_nopriv_calendar', [self::class, 'handle']);
    }

    public static function handle(): void
    {
        $year            = isset($_POST['year'])            ? (int) $_POST['year']            : 0;
        $month_num       = isset($_POST['month_num'])       ? (int) $_POST['month_num']       : 0;
        $today_month_num = isset($_POST['today_month_num']) ? (int) $_POST['today_month_num'] : 0;
        $today_month_day = isset($_POST['today_month_day']) ? (int) $_POST['today_month_day'] : 0;
        $no_past_dates   = isset($_POST['no_past_dates'])   ? (bool) $_POST['no_past_dates']  : true;
        $picked_day      = isset($_POST['month_day'])       ? (int) $_POST['month_day']       : 0;

        if (!$year) {
            wp_send_json(['status' => 'not-found']);
        }

        $month_name = Helpers::getMonthName($month_num);
        $query      = self::getMonthQuery($year, $month_name);

        if (!$query->have_posts()) {
            wp_send_json(['status' => 'not-found']);
        }

        $query->the_post();
        $post_id = get_the_ID();
        wp_reset_postdata();

        $rows = get_field('month-repeat', $post_id);

        if (empty($rows)) {
            wp_send_json(['status' => 'content-empty']);
        }

        $all_days = [];

        foreach ($rows as $row) {
            $day = (int) ($row['day'] ?? 0);
            if (!$day) continue;

            if ($no_past_dates) {
                $is_current_month = ($month_num === $today_month_num);
                $is_past_month    = ($month_num < $today_month_num);

                if ($is_past_month) continue;
                if ($is_current_month && $day < $today_month_day) continue;
            }

            $sluzhby_raw = $row['repeater'] ?? [];
            $sluzhby     = [];

            foreach ($sluzhby_raw as $s) {
                $time = trim($s['time'] ?? '');
                $text = trim($s['text'] ?? '');
                if ($time || $text) {
                    $sluzhby[] = [
                        'time' => $time,
                        'text' => $text,
                    ];
                }
            }

            if (empty($sluzhby)) continue;

            $all_days[] = [
                'day'     => $day,
                'sluzhba' => $sluzhby,
                'saints'  => '',
            ];
        }

        if (empty($all_days)) {
            wp_send_json(['status' => 'content-empty']);
        }

        usort($all_days, fn($a, $b) => $a['day'] <=> $b['day']);
        if ($picked_day) {
            $filtered = array_filter($all_days, fn($d) => $d['day'] === $picked_day);
            $all_days = array_values($filtered);

            if (empty($all_days)) {
                wp_send_json(['status' => 'not-found']);
            }
        } else {
            $all_days = self::getUpcomingDays($all_days, $today_month_day, $month_num, $today_month_num);
        }

        wp_send_json([
            'status' => 'success',
            'days'   => $all_days,
        ]);
    }

    private static function getUpcomingDays(
        array $days,
        int $today_day,
        int $month_num,
        int $today_month_num
    ): array {
        if ($month_num > $today_month_num) {
            return array_slice($days, 0, 3);
        }

        $upcoming = array_values(
            array_filter($days, fn($d) => $d['day'] >= $today_day)
        );

        return array_slice($upcoming, 0, 3);
    }

    private static function getMonthQuery(int $year, string $month_name): \WP_Query
    {
        add_filter('posts_where', [self::class, 'filterByExactTitle'], 10, 2);

        $query = new \WP_Query([
            'post_type'      => 'month-calendar',
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'tax_query'      => [[
                'taxonomy' => 'calendar',
                'field'    => 'slug',
                'terms'    => (string) $year,
            ]],
            'exact_title'    => $month_name,
        ]);

        remove_filter('posts_where', [self::class, 'filterByExactTitle'], 10);
        wp_reset_postdata();

        return $query;
    }

    public static function filterByExactTitle(string $where, \WP_Query $query): string
    {
        $title = $query->get('exact_title');
        if (!$title) return $where;

        global $wpdb;
        $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title = %s", $title);
        return $where;
    }
}
