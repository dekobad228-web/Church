<?php

use App\Helpers;

$type = get_field('type');
$group = get_field($type);
switch ($type):
    case "schedule":
        $date = getdate();

        $week = [
            1 => 'понедельник',
            2 => 'вторник',
            3 => 'среда',
            4 => 'четверг',
            5 => 'пятница',
            6 => 'суббота',
            7 => 'воскресение'
        ];
?>
        <section class="section-schedule">
            <div class="container">
                <div class="section-schedule__block">
                    <h2 class="h1-400 section-schedule__title">
                        <?= $group['title'] ?>
                    </h2>

                    <div class="section-schedule__bottom">
                        <div id="datepicker" class="btn-drop section-schedule__calendar datepicker">
                        </div>
                        <div id="month-container" class="section-schedule__list">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
        break; ?>
    <?php
    case "preview":
        $link = $group['btn_link'] ?? '';
        $text = $group['btn_text'] ?: 'К расписанию >';

        if (!empty($group['date'])) {
            $dateArr  = explode('/', $group['date']);
            $day      = (int) $dateArr[0];
            $mon      = (int) $dateArr[1];
            $year     = (int) $dateArr[2];
        } else {
            $today = getdate();
            $day   = $today['mday'];
            $mon   = $today['mon'];
            $year  = $today['year'];
        }

        $month_num  = $mon - 1;
        $month_name = Helpers::getMonthName($month_num);

        add_filter('posts_where', function (string $where, \WP_Query $q) use ($month_name): string {
            if ($q->get('exact_title') !== $month_name) return $where;
            global $wpdb;
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title = %s", $month_name);
            return $where;
        }, 10, 2);

        $query = new WP_Query([
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

        remove_filter('posts_where', '__return_empty_string', 10);
        wp_reset_postdata();

        $day_data = null;

        if ($query->have_posts()) {
            $query->the_post();
            $rows = get_field('month-repeat', get_the_ID());
            wp_reset_postdata();

            if (!empty($rows)) {
                foreach ($rows as $row) {
                    if ((int) $row['day'] === $day) {
                        $day_data = $row;
                        break;
                    }
                }
            }
        }

        $timestamp = mktime(0, 0, 0, $mon, $day, $year);
    ?>
        <section class="section-preview">
            <div class="container <?= $option['container'] ?? '' ?>">
                <div class="section-preview__block">
                    <div class="section-preview__top">
                        <h2 class="h2-400 section-preview__title">
                            <?= $group['title'] ?>
                        </h2>
                        <p class="p1-400 section-preview__description">
                            <?= $group['text'] ?>
                        </p>
                    </div>
                    <div class="section-preview__list">
                        <?php if ($day_data && !empty($day_data['repeater'])) : ?>
                            <?php foreach ($day_data['repeater'] as $card) : ?>
                                <?php
                                $time = substr($card['time'] ?? '', 0, 5);
                                $text_card = $card['text'] ?? '';
                                ?>
                                <div class="section-preview__card">
                                    <h4 class="h4-400 section-preview__date">
                                        <?= Helpers::getWeekDayName($timestamp); ?><br>
                                        <?= $day ?> <?= $month_name ?> - <?= $time ?>
                                    </h4>
                                    <?php if ($text_card) : ?>
                                        <p class="section-preview__text"><?= $text_card ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="p1">
                                Нет расписания на дату <?= $group['date'] ?? "$day/$mon/$year" ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php if ($day_data && $link) : ?>
                        <a href="<?= $link ?>" class="btn btn--dark section-preview__btn">
                            <?= $text ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php break; ?>
<?php
endswitch;
?>