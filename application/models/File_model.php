<?php

require APPPATH . '/libraries/ImageManipulator.php';
require APPPATH . '/libraries/SimpleImage.php';

define('AVATAR_IMAGE_PATH', 'media/avatar/');
define('COVER_IMAGE_PATH', 'media/covers/');
define('PRODUCT_IMAGE_PATH', 'media/product/');
define('PRODUCT_IMAGE_THUMB_PATH', 'media/product_thumb/');
define('MESSAGES_IMAGE_PATH', 'media/imessage/');
define('ORDER_MESSAGES_IMAGE_PATH', 'media/iomessage/');
define('BRAND_IMAGE_PATH', 'media/brand/');
define('CRM_IMAGE_PATH', 'media/crm/');
define('CATEGORY_ICON_PATH', 'media/category/');
define('BANNER_IMAGE_PATH', 'media/banner/');
define('POST_IMAGE_PATH', 'media/post_upload/');

class File_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	/**
	 *
	 * @param unknown $image
	 * @return boolean
	 */
	public function checkFileImage($image) {
		if ($image['error'] > 0) {
			return false;
		}
		$extension = strtolower(pathinfo($image["name"])['extension']);
		if (($image['size'] < 300000000) && in_array($extension, File_model::$allowedExts) && in_array($image['type'], File_model::$allowedType)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 *
	 * @param unknown $image
	 * @param unknown $path
	 */

	public function saveFile($image, $path) {
		if (file_exists($path)) {
			unlink($path);
		}
		move_uploaded_file($image['tmp_name'], $path);
	}

	/**
	 *
	 * @param unknown $image
	 * @param unknown $con_id
	 * @param unknown $uid
	 * @param unknown $time
	 * @return string
	 */
	public function saveMessageFile($image, $con_id, $uid, $time) {
		$extension = end(explode('.', $image['name']));
		$path = "media/imessage/{$time}-con{$con_id}-uid{$uid}.{$extension}";
		$this->saveFile($image, $path);
		return $path;
	}

	/**
	 *
	 * @param unknown $image
	 * @param number $width
	 * @param number $height
	 * @param string $path
	 */
	public function reSizeFile($image, $width = 200, $height = 200, $path = '') {
		$manipulator = new ImageManipulator($image['tmp_name']);
		// resizing to 200x200
		$newImage = $manipulator->resample($width, $height);
		// saving file to uploads folder
		if ($path == '') {
			$path = 'media/uploads/' . $image['name'];
		}
		$manipulator->save($path);
	}

	/**
	 *
	 * @param unknown $image
	 * @param string $path
	 */
	public function cropFile($image, $path = '') {
		$manipulator = new ImageManipulator($image['tmp_name']);
		$width = $manipulator->getWidth();
		$height = $manipulator->getHeight();
		$size = $width > $height ? $height : $width;
		$newImage = $manipulator->crop(0, 0, $size, $size);
		if ($path == '') {
			$path = 'media/uploads/' . $image['name'];
		}
		$manipulator->save($path);
	}

	/**
	 *
	 * @param unknown $image
	 * @param string $path
	 */

	public function cropCenteralFile($oldPath, $path) {
		$manipulator = new ImageManipulator($oldPath);
		$width = $manipulator->getWidth();
		$height = $manipulator->getHeight();
		$size = $width > $height ? $height : $width;
		$dx = (int)(($width - $size) / 2);
		$dy = (int)(($height - $size) / 2);
		$newImage = $manipulator->crop($dx, $dy, $dx + $size, $dy + $size);
		$manipulator->save($path);
	}

	/**
	 *
	 * @param unknown $image
	 * @param string $path
	 * @param number $mSize
	 */
	public function cropAndResize($image, $path = '', $mSize = 512) {
		$manipulator = new ImageManipulator($image['tmp_name']);
		$width = $manipulator->getWidth();
		$height = $manipulator->getHeight();
		$size = $width > $height ? $height : $width;
		$newImage = $manipulator->crop(0, 0, $size, $size);
		$newImage = $manipulator->resample($mSize, $mSize);
		// saving file to uploads folder
		if ($path == '') {
			$path = 'media/uploads/' . $image['name'];
		}
		if (file_exists($path)) {
			unlink($path);
		}
		$manipulator->save($path);
	}

	public function createThumbnailName($path) {
		$pos = strripos($path, '.');
		return substr($path, 0, $pos) . '-thumb' . substr($path, $pos);
	}

	public function createThumbProduct($path) {
		$pos = strrpos($path, '/');
		$new_path = PRODUCT_IMAGE_THUMB_PATH . substr($path, $pos + 1);
		$this->cropAndResizeThumbNail($path, $new_path, 300);
		return $new_path;
	}

	public function createThumb($path, $size) {
		$pos = strripos($path, '.');
		$thumbPath = substr($path, 0, $pos) . '-' . $size . substr($path, $pos);
		if (!file_exists($thumbPath)) {
			$this->resizeKeepRatioV2($path, $thumbPath, $size);
		}
		return $thumbPath;
	}

	public function createThumbV2($pathEncoded, $size) {
		$path = base64_decode($pathEncoded);
		$pos = strripos($path, '.');
		$thumbPath = 'media/cache/' . $pathEncoded . '-' . $size . substr($path, $pos);
		if (!file_exists($thumbPath)) {
			$this->resizeKeepRatioV2($path, $thumbPath, $size);
		}
		return $thumbPath;
	}

	public function resizeKeepRatio($path, $thumbPath, $size) {
		$manipulator = new ImageManipulator($path);
		$width = $manipulator->getWidth();
		$height = $manipulator->getHeight();
		$maxSize = $width > $height ? $width : $height;

		if ($maxSize > $size && $size > 0) {
			$manipulator->resample($width * $size / $maxSize, $height * $size / $maxSize);
		}
		if (file_exists($thumbPath)) {
			unlink($thumbPath);
		}
		$manipulator->save($thumbPath);
	}

	public function resizeKeepRatioV2($path, $thumbPath, $size) {
		$simpleImage = new SimpleImage();
		$simpleImage->load($path);
		$width = $simpleImage->get_width();
		$height = $simpleImage->get_height();
		$maxSize = $width > $height ? $width : $height;

		if ($maxSize > $size && $size > 0) {
			$simpleImage->thumbnail($width * $size / $maxSize, $height * $size / $maxSize);
		}
		if (file_exists($thumbPath)) {
			unlink($thumbPath);
		}
		$simpleImage->save($thumbPath);
	}

	/**
	 *
	 * @param unknown $oldpath
	 * @param string $path
	 * @param number $mSize
	 */

	public function cropAndResizeThumbNail($oldpath, $path = '', $mSize = 200) {
		$manipulator = new ImageManipulator($oldpath);
		$width = $manipulator->getWidth();
		$height = $manipulator->getHeight();

		$size = $width > $height ? $height : $width;
		$newImage = $manipulator->crop(0, 0, $size, $size);
		$newImage = $manipulator->resample($mSize, $mSize);
		if (file_exists($path)) {
			unlink($path);
		}
		$manipulator->save($path);
	}

	/**
	 *
	 * @param unknown $uid
	 * @param unknown $image
	 * @return string
	 */
	public function createPathAvatar($uid, $image) {
		$extension = pathinfo($image["name"])['extension'];
		$time = time();
		return AVATAR_IMAGE_PATH . "avatar-$uid-$time.$extension";
	}

	public function createFilePath($image, $pre = 'media/') {
		// $extension = end(explode('.', $image['name']));
		$extension = pathinfo($image["name"])['extension'];
		return $pre . $image['name'] . '_' . time() . '.' . $extension;
	}

	public function createFileName($image, $pre = 'media/', $name) {
		// $extension = end(explode('.', $image['name']));
		$extension = pathinfo($image["name"])['extension'];
		return $pre . $name . '_' . time() . '.' . $extension;
	}

	public function createFilePDFPath($file, $pre = 'media/documents/') {
		$extension = end(explode('.', $file['name']));
		return $pre . '-' . time() . '.' . $extension;
	}

	public function createFileIdScan($file, $pre = 'media/idscan/') {
		$extension = end(explode('.', $file['name']));
		return $pre . time() . '.' . $extension;
	}

	public function createPathCover($uid, $image) {
		$extension = end(explode('.', $image['name']));
		$time = time();
		return COVER_IMAGE_PATH . "cover-$uid-$time.$extension";
	}

	public function createPathCrmImage($crm_id, $image) {
		$extension = end(explode('.', $image['name']));
		return CRM_IMAGE_PATH . "crm-$crm_id.$extension";
	}

	public function createPathBrandImage($brand_id, $image) {
		$extension = end(explode('.', $image['name']));
		return BRAND_IMAGE_PATH . "brand-$brand_id.$extension";
	}

	public function createPathCateIcon($cat_id, $image) {
		$extension = end(explode('.', $image['name']));
		return CATEGORY_ICON_PATH . "cat-$cat_id@2x.$extension";
	}

	public function createPathBannerImage($banner_id, $image) {
		$extension = end(explode('.', $image['name']));
		return BANNER_IMAGE_PATH . 'banner-' . $banner_id . '_' . time() . '.' . $extension;
	}

	/**
	 *
	 * @param unknown $images
	 * @return boolean
	 */
	public function checkArrayImage($images) {
		if ($images == null) {
			return false;
		}
		$numOfImage = count($images['name']);
		if ($numOfImage <= 0) {
			return false;
		}

		for ($i = 0; $i < $numOfImage; $i++) {
			$extension = end(explode('.', $images['name'][$i]));
			if (($images['size'][$i] < 30000000) && in_array($extension, File_model::$allowedExts)
				&& in_array($images['type'][$i], File_model::$allowedType)
			) {
				if ($images['error'][$i] > 0) {
					return false;
				}
			} else {
				return false;
			}
		}
		return true;
	}

	/**
	 *
	 * @param unknown $images
	 * @param unknown $product_id
	 * @param unknown $time
	 * @return multitype:string
	 */

	public function saveArrayImage($images, $product_id, $time) {
		$numOfImage = count($images['name']);
		$paths = array();
		for ($i = 0; $i < $numOfImage; $i++) {
			$extension = end(explode('.', $images['name'][$i]));
			$path = PRODUCT_IMAGE_PATH . 'prdid' . $product_id . '-' . ($i + 1) . '-' . $time . '.' . $extension;

			if (file_exists($path)) {
				unlink($path);
			}
			$this->cropCenteralFile($images['tmp_name'][$i], $path);
			$paths[] = $path;
		}

		return $paths;
	}

	/**
	 *
	 * @param unknown $images
	 * @param unknown $time
	 * @return multitype:string
	 */

	public function saveArrayMessageImage($images, $time) {
		$numOfImage = count($images['name']);
		$paths = array();
		for ($i = 0; $i < $numOfImage; $i++) {
			$extension = end(explode('.', $images['name'][$i]));
			$path = MESSAGES_IMAGE_PATH . 'imm' . ($i + 1) . '-' . $time . '.' . $extension;
			if (file_exists($path)) {
				unlink($path);
			}
			move_uploaded_file($images['tmp_name'][$i], $path);
			$paths[] = $path;
		}
		return $paths;
	}

	/**
	 * @param array $images
	 * @param int $time
	 */

	public function saveArrayPostImage($images, $time) {
		$numOfImage = count($images['name']);
		$paths = array();
		for ($i = 0; $i < $numOfImage; $i++) {
			$extension = end(explode('.', $images['name'][$i]));
			$path = POST_IMAGE_PATH . 'post_' . ($i + 1) . '_' . $time . '.' . $extension;
			if (file_exists($path)) {
				unlink($path);
			}
			move_uploaded_file($images['tmp_name'][$i], $path);
			$paths[] = $path;
		}
		return $paths;
	}

	/**
	 *
	 * @param unknown $images
	 * @param unknown $time
	 * @return multitype:string
	 */
	public function saveArrayOrderMessageImage($images, $order_id, $time) {
		$numOfImage = count($images['name']);
		$paths = array();
		for ($i = 0; $i < $numOfImage; $i++) {
			$extension = end(explode('.', $images['name'][$i]));
			$path = ORDER_MESSAGES_IMAGE_PATH . 'iom' . $order_id . ($i + 1) . '-' . $time . '.' . $extension;
			if (file_exists($path)) {
				unlink($path);
			}
			move_uploaded_file($images['tmp_name'][$i], $path);
			$paths[] = $path;
		}
		return $paths;
	}

	public function removeFileAndThumb($path) {
		if ($path == 'media/avatar/sys/noavatar.jpg') {
			return;
		}
		if (file_exists($path)) {
			unlink($path);
		}
		$thumb = $this->createThumbnailName($path);
		if (file_exists($thumb)) {
			unlink($thumb);
		}
	}

	/**
	 *
	 * @param unknown $image
	 * @param number $width
	 * @param number $height
	 * @param string $path
	 */

	public function createThumbByDimension($image, $width, $height) {
		$img = new SimpleImage();
		$pos = strripos($image, '.');
		$path = substr($image, 0, $pos) . '-thumb_' . $width . 'x' . $height . substr($image, $pos);
		$img->load($image)->thumbnail($width, $height)->save($path);
		return $path;
	}

	public function makeThumb($image, $width) {
		if (!file_exists($image)) {
			return $image;
		}
		$pos = strripos($image, '.');
		$path = substr($image, 0, $pos) . '-thumb_' . $width . substr($image, $pos);
		if (!file_exists($path)) {
			$manipulator = new ImageManipulator($image);
			$oWidth = $manipulator->getWidth();
			$oHeight = $manipulator->getHeight();
			$height = (int)($width * $oHeight / $oWidth);
			$newImage = $manipulator->resample($width, $height);
			$manipulator->save($path);
		}
		return $path;
	}

	public function uploadCustom($param, $album_dir) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if ($_FILES[$param]["name"]) {
				if (!is_dir($album_dir)) {
					create_dir($album_dir);
				}
				$config['upload_path'] = $album_dir;
				$config['allowed_types'] = '*';
				$config['max_size'] = 100000000000000;

				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				$video = $this->upload->do_upload($param);
				$video_data = $this->upload->data();
				if ($video) {
					return $config['upload_path'] . $video_data['file_name'];
				} else {
					return null;
				}
			}
		}
		return null;
	}

	private static $allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
	private static $allowedType = array("image/gif", "image/jpeg", "image/jpg", "image/pjpeg", "image/x-png", "image/png",);

}

?>