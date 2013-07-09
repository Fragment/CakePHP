<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
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
		$is_image = getimagesize($source['tmp_name']);

		if ($source && !empty($source))
		{
			$pathinfo = pathinfo($source['name']);
			if (!$filename)
				$filename = $pathinfo['filename'];
			$filename = strtolower(Inflector::slug($filename, '-'));

			$folder = ($folder) ? $folder : $this->table;
			$path = getcwd().'/files/'.$folder;
			$path .= ($field) ? '/'.$field : '';

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

					if(!is_dir($curr_path))
					{
						$old = umask(0);
						mkdir($curr_path, 0777);
						umask($old);
					}
					
					$new_filename = $filename.'-'.date('yhis').'.'.strtolower($pathinfo['extension']);
					// $x = 0;
					// $new_filename = null;
					// do
					// {
					// 	$new_filename = $filename;
					// 	if ($x > 0)
					// 		$new_filename .= '-'.$x;
					// 	$new_filename .= '.'.strtolower($pathinfo['extension']);
					// 		
					// 	if (file_exists($curr_path.'/'.$new_filename))
					// 		$new_filename = null;
					// 	$x++;
					// }
					// while ($new_filename == null);
					$curr_filename = $new_filename;

					$curr_path .= '/'.$curr_filename;
					if ($upload_type == 3)
						imagepng($dest_img, $curr_path, 0);
					else
						imagejpeg($dest_img, $curr_path, 75);

					imagedestroy($dest_img);
				}

				return $curr_filename;
			}
			else
			{
				$path .= '/'.$filename;
				move_uploaded_file($source['tmp_name'], $path);
				return $filename;
			}
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
			'image/pjpeg'
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
