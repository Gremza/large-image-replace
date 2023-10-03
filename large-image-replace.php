<?php
/**
 * Large Image Replace
 *
 * @package       LARGEIMAGE
 * @author        marsel preci
 * @license       gplv2
 * @version       1.0.1
 *
 * @wordpress-plugin
 * Plugin Name:   Large Image Replace
 * Plugin URI:    https://gremza.com
 * Description:   If you want to save storage and not import images bigger than the large size of wp. This plugin with help. It replace original file with the large size generated. It saves a ton of space espacialu for the webesites that use a lot of images.
 * Version:       1.0.0
 * Author:        marsel preci
 * Author URI:    https://gremza.com
 * Text Domain:   large-image-replace
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with Large Image Replace. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Include your custom code here.

function replace_uploaded_image($image_data) {
    // if there is no large image : return
if (!isset($image_data['sizes']['large'])) return $image_data;

// paths to the uploaded image and the large image
$upload_dir = wp_upload_dir();
$uploaded_image_location = $upload_dir['basedir'] . '/' .$image_data['file'];
// $large_image_location = $upload_dir['path'] . '/'.$image_data['sizes']['large']['file']; // ** This only works for new image uploads - fixed for older images below.
$current_subdir = substr($image_data['file'],0,strrpos($image_data['file'],"/"));
//  $large_image_location = $upload_dir['basedir'] . '/'.$current_subdir.'/'.$image_data['sizes']['large']['file'];
$current_subdir = substr($image_data['file'],0,strrpos($image_data['file'],"/"));
$large_image_location = $upload_dir['basedir'] . '/'.$current_subdir.'/'.$image_data['sizes']['large']['file'];
// delete the uploaded image
unlink($uploaded_image_location);

// rename the large image
rename($large_image_location,$uploaded_image_location);

// update image metadata and return them
$image_data['width'] = $image_data['sizes']['large']['width'];
$image_data['height'] = $image_data['sizes']['large']['height'];
unset($image_data['sizes']['large']);

return $image_data;
}

add_filter('wp_generate_attachment_metadata','replace_uploaded_image');



