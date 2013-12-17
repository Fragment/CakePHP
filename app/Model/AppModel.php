<?php
/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model
{
	public function upload($source, $filename = null, $size = null, $field = null, $folder = null)
	{
		if ($source && !empty($source))
		{
			$processed = false;
			$pathinfo = pathinfo($source['name']);
			if ($filename)
				$filename = strtolower(Inflector::slug($filename, '-')).'.'.strtolower($pathinfo['extension']);
			else
				$filename = strtolower(Inflector::slug($pathinfo['filename'], '-')).'-'.date('mdy').'-'.rand(100, 999).'.'.strtolower($pathinfo['extension']);
			$folder = ($folder) ? $folder : $this->table;
			$path = getcwd().'/files/'.$folder;
			$path .= ($field) ? '/'.$field : '';

			$is_image = getimagesize($source['tmp_name']);
			if ($is_image)
			{
				if (!$size)
					$size = array(750);
				elseif (!is_array($size))
					$size = array($size);

				$upload_info = getimagesize($source['tmp_name']);
				$upload_width  = $upload_info[0];
				$upload_height = $upload_info[1];
				$upload_type = $upload_info[2];

				foreach ($size as $size_folder => $max_width)
				{
					if ($upload_type == 3)
						$source_img = imagecreatefrompng($source['tmp_name']);
					else
						$source_img = imagecreatefromjpeg($source['tmp_name']);				

					$max_height = $max_width / .75;
					if ($upload_width > $max_width || $upload_height > $max_height)
					{
						$ratio = $upload_width / $upload_height;
						$newX = $max_width;
						$newY = $newX / $ratio;

						if ($newY > $max_height)
						{
							$newY = $max_height;
							$newX = $newY * $ratio;
						}
						$dest_img = imagecreatetruecolor($newX, $newY);

						if ($upload_type == 3)
						{
							imagecolortransparent($dest_img, imagecolorallocatealpha($dest_img, 0, 0, 0, 127));
							imagealphablending($dest_img, false);
							imagesavealpha($dest_img, true);
						}

						imagecopyresampled($dest_img, $source_img, 0, 0, 0, 0, $newX, $newY, $upload_width, $upload_height);
					}
					else
					{
						$dest_img = imagecreatetruecolor($upload_width, $upload_height);

						if ($upload_type == 3)
						{
							imagecolortransparent($dest_img, imagecolorallocatealpha($dest_img, 0, 0, 0, 127));
							imagealphablending($dest_img, false);
							imagesavealpha($dest_img, true);
						}

						imagecopy($dest_img, $source_img, 0, 0, 0, 0, $upload_width, $upload_height);
					}

					$curr_path = $path;
					$curr_path .= ($size_folder) ? '/'.$size_folder : '';

					if (!is_dir($curr_path))
					{
						$old = umask(0);
						mkdir($curr_path, 0777);
						umask($old);
					}
					
					
					$curr_path .= '/'.$filename;
					if ($upload_type == 3)
					{
						if (imagepng($dest_img, $curr_path, 0))
							$processed = true;
					}
					else
					{
						if (imagejpeg($dest_img, $curr_path, 75))
							$processed = true;
					}

					imagedestroy($dest_img);
				}
			}
			else
			{
				$path .= '/'.$filename;
				if (move_uploaded_file($source['tmp_name'], $path))
					$processed = true;
			}

			if ($processed)
				return $filename;
		}
	}

	public function checkMime($check)
	{
		$mimeTypes = array(
			'image/gif',
			'image/jpeg',
			'image/png',
			'image/tiff',
			'image/jpg',
			'image/pjpeg',
			// 'application/acad',
			// 'application/dxf',
			// 'application/msword',
			// 'application/pdf',
			// 'application/vnd.ms-excel',
			// 'application/rtf',
			// 'application/x-visio',
			// 'text/csv',
			// 'text/directory',
			// 'text/html',
			// 'text/plain',
			// 'text/vcard',
			// 'text/x-vcard'
		);
		$check = array_shift($check);
		$mime = (isset($check['tmp_name'])) ? $check['type'] : null;
		return in_array($mime, $mimeTypes);
    }
}
