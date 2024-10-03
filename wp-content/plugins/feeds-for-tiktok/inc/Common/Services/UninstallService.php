<?php

/**
 * Service responsible with plugin uninstall functionality.
 *
 * @package tiktok-feeds
 */

namespace SmashBalloon\TikTokFeeds\Common\Services;

use SmashBalloon\TikTokFeeds\Common\Container;

/**
 * Plugin Uninstall Service class.
 */
class UninstallService
{
	/**
	 * Register.
	 *
	 * @return void
	 */
	public function register()
	{
		register_uninstall_hook(SBTT_PLUGIN_FILE, [ self::class, 'uninstall' ]);
	}

	/**
	 * Remove plugin database data. Drop tables when the plugin is deleted from WordPress Admin Plugins page.
	 *
	 * @return void
	 */
	public static function uninstall()
	{
		$global_settings = get_option('sbtt_global_settings');

		if (isset($global_settings['preserve_settings']) && $global_settings['preserve_settings']) {
			return;
		}

		self::delete_db_tables();

		self::delete_options();

		self::delete_cron_jobs();

		self::delete_upload_folder();
	}

	/**
	 * Remove plugin database data.
	 *
	 * @return void
	 */
	public static function delete_db_tables()
	{
		Container::get_instance()->get('DBManager')->drop_db_tables();
	}

	/**
	 * Remove plugin options.
	 *
	 * @return void
	 */
	public static function delete_options()
	{
		$options_to_delete = [
			'sbtt_global_settings',
			'sbtt_statuses',
			'sbtt_db_version',
		];

		foreach ($options_to_delete as $option) {
			delete_option($option);
		}
	}

	/**
	 * Remove plugin cron jobs.
	 *
	 * @return void
	 */
	public static function delete_cron_jobs()
	{
		$cron_jobs = [
			'sbtt_refresh_token_routine',
			'sbtt_feed_update_routine',
			'sbtt_resize_post_images',
		];

		foreach ($cron_jobs as $cron_job) {
			wp_clear_scheduled_hook($cron_job);
		}
	}

	/**
	 * Remove plugin upload folder.
	 *
	 * @return void
	 */
	public static function delete_upload_folder()
	{
		$upload     = wp_upload_dir();
		$upload_dir = trailingslashit($upload['basedir']) . SBTT_UPLOAD_FOLDER_NAME;

		if (file_exists($upload_dir)) {
			$files = glob($upload_dir . '/*');
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}

			global $wp_filesystem;
			$wp_filesystem->delete($upload_dir, true);
		}
	}
}
