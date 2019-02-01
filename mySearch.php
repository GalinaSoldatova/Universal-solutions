<?php

/* поиск по сайту с помощью БД, преимущественно для MODX*/

function search ($query) 
{ 
	//TV - параметры
	$imgTV = 8;
	$articulTV = 9;
	$brandTV = 10;
	$colorTV = 12;
	//нужные шаблоны через запятую
	$templates= '32,39,21,23,19,8,20,11,22';

	global $modx;
    $query = trim($query);
    $query = $modx->db->escape($query);	
    $query = htmlspecialchars($query);
	//кодировка
	$query = iconv("UTF-8", "windows-1251", $query);
	
    if (!empty($query)) 
    { 
        if (strlen($query) < 3) {
            $text = '<p>Слишком короткий поисковый запрос.</p>';
        } else if (strlen($query) > 128) {
            $text = '<p>Слишком длинный поисковый запрос.</p>';
        } else { 
			
			$q = "SELECT * FROM ".$modx->getFullTableName('site_content')." WHERE published=1 AND deleted=0 AND template IN ($templates) AND `pagetitle` LIKE  '%$query%'";
            $result = $modx->db->query($q);

            if ($modx->db->getAffectedRows() > 0) { 
                $row = $modx->db->getRow($result); 
                $num = $modx->db->getRecordCount($result);
				
                $text = '<p>По запросу <b>'.$query.'</b> найдено совпадений: '.$num.'</p>';

				do {
					// Делаем запрос, получающий TV-параметры
					$rowid = $row['id']; 
					$q1 = "SELECT * FROM ".$modx->getFullTableName('site_tmplvar_contentvalues')." WHERE  `contentid`=".$rowid;	
					$result1 = $modx->db->query($q1);
					
					if ($modx->db->getAffectedRows() > 0) {
						$row1 = $modx->db->getRow($result1); 
						do { 
							//подставляем тв параметры
							if ($row1['tmplvarid'] == $imgTV) $img=$row1['value'];
							if ($row1['tmplvarid'] == $articulTV) $articul=$row1['value'];
							if ($row1['tmplvarid'] == $brandTV) $brand=$row1['value'];
							if ($row1['tmplvarid'] == $colorTV) $color=$row1['value'];

						} while ( $row1 = $modx->db->getRow($result1));
					}
                    
                    if ($img == "") $img="/assets/images/photo.jpg"; //путь к nophoto

					//шаблон вывода
					$text .= '<div class="catalog_item_wrapper_1">
								<div class="catalog_item_1">
									<div class="img">
										<a href="[~'.$row['id'].'~]">
											<img src="'.$img.'" alt="'.$row['pagetitle'].'">
										</a>
									</div>
								<div class="text">
									<div class="title" style="height: initial;">
										<a href="[~'.$row['id'].'~]">
											'.$row['pagetitle'].'<br>
											Арт.'.$articul.'
										</a>
									</div>
								</div>
							</div>
						</div>';	

				} while ($row = $modx->db->getRow($result)); 
			} else {
				$text = '<p>По вашему запросу ничего не найдено.</p>';
            }
        } 
    } else {
        $text = '<p>Задан пустой поисковый запрос.</p>';
    }
    return $text; 
} 

if (!empty($_GET['search'])) { 
    $search_result = search ($_GET['search']); 
    echo $search_result; 
}
