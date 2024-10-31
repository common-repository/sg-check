<?php
/**
 * Template Admin Page
 *
 * @author NasimNet <nasimnet.ir@gmail.com>
 * @package sg-check/includes
 * @since 1.5
 */

?>

<div class="wrap">
	<div class="postbox">
		<div class="postbox-header">
			<h2 class="hndle ui-sortable-handle">
				<?php esc_html_e( 'Source Guardian status', 'sg-check' ); ?>
			</h2>
		</div>
		<div class="inside">
			<div class="main">
				<div id="sg-content">
                    <?php echo $sg_details; //phpcs:ignore ?>
				</div>
			</div>
		</div>
	</div>

	<table class="sgcheck-status-table widefat">
		<thead>
			<tr>
				<th colspan="3"><h2><?php esc_html_e( 'Server Information', 'sg-check' ); ?></h2></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $server_info as $info ) {
				printf(
					'<tr><td>%s</td><td>%s</td></tr>',
					esc_attr( $info['title'] ),
					esc_html( $info['desc'] )
				);
			}
			?>
		</tbody>
	</table>
</div>
