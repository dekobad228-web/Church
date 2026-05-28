<?php
/*
Plugin Name: No Category Base (WPML)
Version: 1.5.5
Plugin URI: https://nocatwp.com
Description: Removes '/category' from your category permalinks. WPML compatible.
Author: TRS Plugins
Author URI: https://trsplugins.com/
License: GPLv2 or later
Text Domain: no-category-base-wpml
*/
/*
Copyright 2025 TRS Plugins
Copyright 2015 Marios Alexandrou
Copyright 2011 Mines (email: hi@mines.io)
Copyright 2008 Saurabh Gupta (email: saurabh0@gmail.com)
Based on the work by Saurabh Gupta (email : saurabh0@gmail.com)
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'NCBW_FILE',    __FILE__ );
define( 'NCBW_DIR',     plugin_dir_path( __FILE__ ) );
define( 'NCBW_URL',     plugin_dir_url( __FILE__ ) );
define( 'NCBW_VERSION', '1.5.5' );

/* Opt-in */
require_once NCBW_DIR . 'includes/class-optin.php';
add_action( 'plugins_loaded', function() {
	NCBW_Optin::instance();
} );

/* hooks */
register_activation_hook(__FILE__,    'no_category_base_refresh_rules');
register_activation_hook(__FILE__,    'ncbw_optin_on_activation');
register_deactivation_hook(__FILE__,  'no_category_base_deactivate');
add_action( 'upgrader_process_complete', 'ncbw_optin_on_upgrade', 10, 2 );

function ncbw_optin_on_activation() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-optin.php';
	NCBW_Optin::instance()->on_activation();
}

function ncbw_optin_on_upgrade( $upgrader, $hook_extra ) {
	if ( empty( $hook_extra['action'] ) || 'update' !== $hook_extra['action'] ) {
		return;
	}

	if ( empty( $hook_extra['type'] ) || 'plugin' !== $hook_extra['type'] ) {
		return;
	}

	$updated_plugins = array();
	if ( ! empty( $hook_extra['plugins'] ) && is_array( $hook_extra['plugins'] ) ) {
		$updated_plugins = $hook_extra['plugins'];
	} elseif ( ! empty( $hook_extra['plugin'] ) ) {
		$updated_plugins = array( $hook_extra['plugin'] );
	}

	if ( in_array( plugin_basename( __FILE__ ), $updated_plugins, true ) ) {
		ncbw_optin_on_activation();
	}
}

/* actions */
add_action('created_category',  'no_category_base_refresh_rules');
add_action('delete_category',   'no_category_base_refresh_rules');
add_action('edited_category',   'no_category_base_refresh_rules');
add_action('init',              'no_category_base_permastruct');
add_action('admin_menu',        'ncbw_admin_menu');
add_action('admin_enqueue_scripts', 'ncbw_enqueue_admin_assets');

/* filters */
add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
add_filter('query_vars',             'no_category_base_query_vars');    // Adds 'category_redirect' query variable
add_filter('request',                'no_category_base_request');       // Redirects if 'category_redirect' is set

function no_category_base_refresh_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}

function no_category_base_deactivate() {
	remove_filter( 'category_rewrite_rules', 'no_category_base_rewrite_rules' ); // We don't want to insert our custom rules again
	no_category_base_refresh_rules();
}

/**
 * Removes category base.
 *
 * @return void
 */
function no_category_base_permastruct()
{
	global $wp_rewrite;
	global $wp_version;

	if ( $wp_version >= 3.4 ) {
		$wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
	} else {
		$wp_rewrite->extra_permastructs['category'][0] = '%category%';
	}
}

/**
 * Adds our custom category rewrite rules.
 *
 * @param  array $category_rewrite Category rewrite rules.
 *
 * @return array
 */
function no_category_base_rewrite_rules($category_rewrite) {
	global $wp_rewrite;
	$category_rewrite=array();

	/* WPML is present: temporary disable terms_clauses filter to get all categories for rewrite */
	if ( class_exists( 'Sitepress' ) ) {
		global $sitepress;

		remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
		$categories = get_categories( array( 'hide_empty' => false ) );
		//Fix provided by Albin here https://wordpress.org/support/topic/bug-with-wpml-2/#post-8362218
		//add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
		add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ), 10, 4 );
	} else {
		$categories = get_categories( array( 'hide_empty' => false ) );
	}

	foreach( $categories as $category ) {
		$category_nicename = $category->slug;

		if ( $category->parent == $category->cat_ID ) {
			$category->parent = 0;
		} elseif ( $category->parent != 0 ) {
			$category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
		}

		$category_rewrite['('.$category_nicename.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
		$category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
		$category_rewrite['('.$category_nicename.')/?$'] = 'index.php?category_name=$matches[1]';
	}

	// Redirect support from Old Category Base
	$old_category_base = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
	$old_category_base = trim( $old_category_base, '/' );
	$category_rewrite[$old_category_base.'/(.*)$'] = 'index.php?category_redirect=$matches[1]';

	return $category_rewrite;
}

function no_category_base_query_vars($public_query_vars) {
	$public_query_vars[] = 'category_redirect';
	return $public_query_vars;
}

/**
 * Handles category redirects.
 *
 * @param $query_vars Current query vars.
 *
 * @return array $query_vars, or void if category_redirect is present.
 */
function no_category_base_request($query_vars) {
	if( isset( $query_vars['category_redirect'] ) ) {
		$catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
		status_header( 301 );
		header( "Location: $catlink" );
		exit();
	}

	return $query_vars;
}

/* =========================================================
   ADMIN: Settings Page & Upsell
   ========================================================= */

/**
 * Whether Pro is active — Pro plugin overrides this via filter.
 */
function ncbw_is_pro_active() {
	return (bool) apply_filters( 'ncbw_is_pro_active', false );
}

/**
 * Register admin page under Settings.
 */
function ncbw_admin_menu() {
	add_options_page(
		__( 'No Category Base', 'no-category-base-wpml' ),
		__( 'No Category Base', 'no-category-base-wpml' ),
		'manage_options',
		'no-category-base-wpml',
		'ncbw_render_admin_page'
	);
}

/**
 * Enqueue admin CSS/JS on our page only.
 */
function ncbw_enqueue_admin_assets( $hook ) {
	if ( 'settings_page_no-category-base-wpml' !== $hook ) {
		return;
	}
	wp_enqueue_style(
		'ncbw-admin',
		NCBW_URL . 'assets/css/admin.css',
		array(),
		NCBW_VERSION
	);
}

/**
 * Build the list of admin tabs.
 * Pro plugin hooks in to add real tabs and remove the Go Pro tab.
 */
function ncbw_get_admin_tabs() {
	$tabs = array(
		'settings' => __( 'Settings', 'no-category-base-wpml' ),
		'help'     => __( 'Help', 'no-category-base-wpml' ),
	);

	if ( ! ncbw_is_pro_active() ) {
		$tabs['upgrade'] = __( '⭐ Go Pro', 'no-category-base-wpml' );
	}

	$tabs['url_control']     = __( 'URL Control', 'no-category-base-wpml' );
	$tabs['conflict']        = __( 'Conflict Handler', 'no-category-base-wpml' );
	$tabs['redirects']       = __( 'Redirects & 404', 'no-category-base-wpml' );
	$tabs['dashboard']       = __( 'Dashboard', 'no-category-base-wpml' );
	$tabs['wpml_advanced']   = __( 'WPML Advanced', 'no-category-base-wpml' );
	$tabs['import_export']   = __( 'Import / Export', 'no-category-base-wpml' );

	return apply_filters( 'ncbw_admin_tabs', $tabs );
}

/**
 * Render the settings page.
 */
function ncbw_render_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$tabs        = ncbw_get_admin_tabs();
	$current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'settings';
	if ( ! array_key_exists( $current_tab, $tabs ) ) {
		$current_tab = 'settings';
	}

	$is_pro      = ncbw_is_pro_active();
	$pro_tabs    = array( 'url_control', 'conflict', 'redirects', 'dashboard', 'wpml_advanced', 'import_export' );
	$page_url    = admin_url( 'options-general.php?page=no-category-base-wpml' );
	$upgrade_url = admin_url( 'options-general.php?page=no-category-base-wpml&tab=upgrade' );
	?>
	<div class="wrap ncbw-admin-wrap">
		<h1><?php esc_html_e( 'No Category Base (WPML)', 'no-category-base-wpml' ); ?></h1>

		<nav class="nav-tab-wrapper">
			<?php foreach ( $tabs as $slug => $label ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'tab', $slug, $page_url ) ); ?>"
				   class="nav-tab <?php echo $current_tab === $slug ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_html( $label ); ?>
				</a>
			<?php endforeach; ?>
		</nav>

		<div class="ncbw-tab-content">
			<?php
			// Fire action so Pro can render real content for its tabs.
			$rendered = false;
			do_action( 'ncbw_render_tab_' . $current_tab, $is_pro );
			// Check if Pro rendered this tab.
			$rendered = apply_filters( 'ncbw_tab_rendered_' . $current_tab, false );

			if ( ! $rendered ) {
				// Free plugin handles built-in tabs; blurs Pro-only ones.
				switch ( $current_tab ) {
					case 'settings':
						ncbw_render_tab_settings();
						break;
					case 'help':
						ncbw_render_tab_help();
						break;
					case 'upgrade':
						ncbw_render_tab_upgrade();
						break;
					default:
						if ( in_array( $current_tab, $pro_tabs, true ) ) {
							ncbw_render_tab_blurred( $current_tab, $upgrade_url );
						}
						break;
				}
			}
			?>
		</div>
	</div>
	<?php
}

/**
 * Settings tab — core plugin settings.
 */
function ncbw_render_tab_settings() {
	?>
	<div class="ncbw-card">
		<h2><?php esc_html_e( 'Category Permalink Settings', 'no-category-base-wpml' ); ?></h2>
		<p><?php esc_html_e( 'This plugin is active and running. The /category/ base is automatically removed from all your category URLs.', 'no-category-base-wpml' ); ?></p>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'Status', 'no-category-base-wpml' ); ?></th>
				<td><span class="ncbw-badge ncbw-badge--active">&#10003; <?php esc_html_e( 'Active', 'no-category-base-wpml' ); ?></span></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'Category URL example', 'no-category-base-wpml' ); ?></th>
				<td>
					<code><?php echo esc_html( trailingslashit( home_url() ) ); ?>my-category/</code>
					<p class="description"><?php esc_html_e( 'Instead of /category/my-category/', 'no-category-base-wpml' ); ?></p>
				</td>
			</tr>
		</table>
		<p>
			<a href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>" class="button button-secondary">
				<?php esc_html_e( 'Flush Rewrite Rules', 'no-category-base-wpml' ); ?>
			</a>
		</p>
	</div>
	<?php
}

/**
 * Help tab with tutorial and demo links.
 */
function ncbw_render_tab_help() {
	?>
	<div class="ncbw-card">
		<h2><?php esc_html_e( 'Documentation & Tutorial', 'no-category-base-wpml' ); ?></h2>
		<p><?php esc_html_e( 'Use the live demo to review the plugin flow, check the settings layout, and share a guided walkthrough with customers.', 'no-category-base-wpml' ); ?></p>
		<p>
			<a href="https://demo.nocatwp.com/" target="_blank" rel="noopener noreferrer" class="button button-primary">
				<?php esc_html_e( 'Open Demo / Tutorial', 'no-category-base-wpml' ); ?>
			</a>
		</p>
	</div>
	<div class="ncbw-card">
		<h2><?php esc_html_e( 'Quick Help', 'no-category-base-wpml' ); ?></h2>
		<ul style="list-style:disc;padding-left:20px;">
			<li><?php esc_html_e( 'Activate the plugin and save permalinks once if category routes were cached before activation.', 'no-category-base-wpml' ); ?></li>
			<li><?php esc_html_e( 'The free version removes the default WordPress category base from category archive URLs.', 'no-category-base-wpml' ); ?></li>
			<li><?php esc_html_e( 'Use the live demo above when you need a visual product walkthrough.', 'no-category-base-wpml' ); ?></li>
		</ul>
	</div>
	<?php
}

/**
 * Go Pro / Upgrade tab.
 */
function ncbw_render_tab_upgrade() {
	$license_url = admin_url( 'options-general.php?page=no-category-base-wpml&tab=license' );
	$features = array(
		array(
			'icon'  => '🔗',
			'title' => __( 'Custom Taxonomy Base Removal', 'no-category-base-wpml' ),
			'desc'  => __( 'Remove base slugs from tags, product categories, and any custom taxonomy — not just categories.', 'no-category-base-wpml' ),
		),
		array(
			'icon'  => '⚠️',
			'title' => __( 'Conflict Detection & Resolution', 'no-category-base-wpml' ),
			'desc'  => __( 'Detect slug collisions between pages, posts, and taxonomies with one-click fixes.', 'no-category-base-wpml' ),
		),
		array(
			'icon'  => '↩️',
			'title' => __( 'Redirect Manager + 404 Tracker', 'no-category-base-wpml' ),
			'desc'  => __( 'Log all redirects with hit counts. Track 404 errors and get automatic redirect suggestions.', 'no-category-base-wpml' ),
		),
		array(
			'icon'  => '🔄',
			'title' => __( 'Preview Mode & Rollback', 'no-category-base-wpml' ),
			'desc'  => __( 'Preview URL changes before applying, and roll back to previous structures with one click.', 'no-category-base-wpml' ),
		),
		array(
			'icon'  => '🌍',
			'title' => __( 'WPML Advanced: Translated Slugs', 'no-category-base-wpml' ),
			'desc'  => __( 'Set per-language slug structures and validate canonical/hreflang tags for multilingual SEO.', 'no-category-base-wpml' ),
		),
		array(
			'icon'  => '📦',
			'title' => __( 'Import / Export Settings', 'no-category-base-wpml' ),
			'desc'  => __( 'Save and restore all permalink settings and redirects across multiple sites.', 'no-category-base-wpml' ),
		),
	);
	?>
	<div class="ncbw-upgrade-hero">
		<h2><?php esc_html_e( 'Unlock the full power of permalink management', 'no-category-base-wpml' ); ?></h2>
		<p><?php esc_html_e( '$10/month — custom taxonomies, conflict handling, redirects, WPML advanced, and more.', 'no-category-base-wpml' ); ?></p>
		<a href="https://www.digitalme.cc/" target="_blank" rel="noopener noreferrer" class="button button-primary button-hero">
			<?php esc_html_e( 'Get Pro License', 'no-category-base-wpml' ); ?>
		</a>
		<?php if ( class_exists( 'NCBW_Pro' ) ) : ?>
			<a href="<?php echo esc_url( $license_url ); ?>" class="button button-secondary button-hero" style="margin-left:10px;">
				<?php esc_html_e( 'Enter License Key', 'no-category-base-wpml' ); ?>
			</a>
		<?php endif; ?>
	</div>

	<div class="ncbw-features-grid">
		<?php foreach ( $features as $f ) : ?>
			<div class="ncbw-feature-card">
				<span class="ncbw-feature-icon"><?php echo $f['icon']; ?></span>
				<h3><?php echo esc_html( $f['title'] ); ?></h3>
				<p><?php echo esc_html( $f['desc'] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Render a blurred placeholder for a Pro-only tab.
 *
 * @param string $tab         Tab slug.
 * @param string $upgrade_url URL to the upgrade tab.
 */
function ncbw_render_tab_blurred( $tab, $upgrade_url ) {
	$previews = array(
		'url_control'   => array(
			'title' => __( 'URL & Permalink Control', 'no-category-base-wpml' ),
			'rows'  => array(
				array( __( 'Remove tag base (/tag/)', 'no-category-base-wpml' ), 'toggle' ),
				array( __( 'Remove custom taxonomy base', 'no-category-base-wpml' ), 'toggle' ),
				array( __( 'Custom permalink per post type', 'no-category-base-wpml' ), 'text' ),
				array( __( 'Per-language URL structure (WPML)', 'no-category-base-wpml' ), 'text' ),
			),
		),
		'conflict'      => array(
			'title' => __( 'Conflict Handler', 'no-category-base-wpml' ),
			'rows'  => array(
				array( __( 'Slug conflict scanner', 'no-category-base-wpml' ), 'button' ),
				array( __( 'Detected conflicts', 'no-category-base-wpml' ), 'table' ),
				array( __( 'One-click conflict resolution', 'no-category-base-wpml' ), 'button' ),
				array( __( 'Preview changes before applying', 'no-category-base-wpml' ), 'toggle' ),
			),
		),
		'redirects'     => array(
			'title' => __( 'Redirects & 404 Tracker', 'no-category-base-wpml' ),
			'rows'  => array(
				array( __( 'Active redirects (12)', 'no-category-base-wpml' ), 'table' ),
				array( __( 'Recent 404 errors (7)', 'no-category-base-wpml' ), 'table' ),
				array( __( 'Add manual redirect', 'no-category-base-wpml' ), 'button' ),
				array( __( 'Auto-suggest redirects from 404s', 'no-category-base-wpml' ), 'toggle' ),
			),
		),
		'dashboard'     => array(
			'title' => __( 'Dashboard', 'no-category-base-wpml' ),
			'rows'  => array(
				array( __( 'URL changes this month', 'no-category-base-wpml' ), 'stat' ),
				array( __( 'Active redirects', 'no-category-base-wpml' ), 'stat' ),
				array( __( 'Unresolved 404s', 'no-category-base-wpml' ), 'stat' ),
				array( __( 'SEO indexing status', 'no-category-base-wpml' ), 'badge' ),
			),
		),
		'wpml_advanced' => array(
			'title' => __( 'WPML Advanced', 'no-category-base-wpml' ),
			'rows'  => array(
				array( __( 'Translated category slugs per language', 'no-category-base-wpml' ), 'text' ),
				array( __( 'Canonical tag validation', 'no-category-base-wpml' ), 'button' ),
				array( __( 'Hreflang audit', 'no-category-base-wpml' ), 'button' ),
				array( __( 'Per-language permalink structure', 'no-category-base-wpml' ), 'text' ),
			),
		),
		'import_export' => array(
			'title' => __( 'Import / Export', 'no-category-base-wpml' ),
			'rows'  => array(
				array( __( 'Export all settings as JSON', 'no-category-base-wpml' ), 'button' ),
				array( __( 'Import settings from file', 'no-category-base-wpml' ), 'button' ),
				array( __( 'Multisite: view all sites', 'no-category-base-wpml' ), 'table' ),
				array( __( 'Apply settings to all sites', 'no-category-base-wpml' ), 'button' ),
			),
		),
	);

	$preview = isset( $previews[ $tab ] ) ? $previews[ $tab ] : array( 'title' => ucfirst( $tab ), 'rows' => array() );
	?>
	<div class="ncbw-blurred-wrap">
		<div class="ncbw-blurred-content" aria-hidden="true">
			<div class="ncbw-card">
				<h2><?php echo esc_html( $preview['title'] ); ?></h2>
				<table class="form-table">
					<?php foreach ( $preview['rows'] as $row ) : ?>
						<tr>
							<th><?php echo esc_html( $row[0] ); ?></th>
							<td>
								<?php if ( $row[1] === 'toggle' ) : ?>
									<input type="checkbox" disabled checked> <label><?php esc_html_e( 'Enable', 'no-category-base-wpml' ); ?></label>
								<?php elseif ( $row[1] === 'button' ) : ?>
									<button class="button" disabled><?php esc_html_e( 'Run', 'no-category-base-wpml' ); ?></button>
								<?php elseif ( $row[1] === 'stat' ) : ?>
									<span class="ncbw-stat">24</span>
								<?php elseif ( $row[1] === 'badge' ) : ?>
									<span class="ncbw-badge ncbw-badge--active"><?php esc_html_e( 'Good', 'no-category-base-wpml' ); ?></span>
								<?php elseif ( $row[1] === 'table' ) : ?>
									<span class="ncbw-badge"><?php esc_html_e( 'View table', 'no-category-base-wpml' ); ?></span>
								<?php else : ?>
									<input type="text" class="regular-text" value="/%category%/" disabled>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>
		<div class="ncbw-blurred-overlay">
			<div class="ncbw-upgrade-cta">
				<span class="dashicons dashicons-lock"></span>
				<h3><?php esc_html_e( 'Pro Feature', 'no-category-base-wpml' ); ?></h3>
				<p><?php esc_html_e( 'Upgrade to Pro to unlock this feature.', 'no-category-base-wpml' ); ?></p>
				<a href="<?php echo esc_url( $upgrade_url ); ?>" class="button button-primary">
					<?php esc_html_e( '⭐ Upgrade to Pro', 'no-category-base-wpml' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
}