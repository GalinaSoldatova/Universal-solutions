
    <?php
    
    $cur_url = $_SERVER['REQUEST_URI'];
    $urls = explode('/', $cur_url);
    
    $crumbs = array(); 

    if (!empty($urls) && $cur_url != '/') {
        foreach ($urls as $key => $value) {
            $prev_urls = array();
            for ($i = 0; $i <= $key; $i++) {
                $prev_urls[] = $urls[$i];
            }
            if ($key == count($urls) - 1)
                $crumbs[$key]['url'] = '';
            elseif (!empty($prev_urls))
                $crumbs[$key]['url'] = count($prev_urls) > 1 ? implode('/', $prev_urls) : '/';
            
            if(stristr($value, '.html') !== FALSE) $value=stristr($value, '.', true);

            $crumbs[$key]['end'] = '.html';

            switch ($value) {
                case '' : $crumbs[$key]['text'] = 'Главная'; $crumbs[$key]['end'] = '';
                    break;
                case 'produkciya' : $crumbs[$key]['text'] = 'Продукция';
                    break;
                case 'otzyvy' : $crumbs[$key]['text'] = 'Отзывы';
                    break;
                case 'kontakty' : $crumbs[$key]['text'] = 'Контакты';
                    break;
                case 'assortiment' : $crumbs[$key]['text'] = 'Ассортимент';
                    break;                 
                default :  
                    $rr = mysql_query("SELECT * FROM catalog_cat WHERE alias='".$value."'");
                    if ($rr && mysql_num_rows($rr)){
                        $row = mysql_fetch_assoc($rr);
                        $crumbs[$key]['text'] = $row['title'];
                    }
                    break;
            }
            
        }
    }
     ?>


    <?php if (!empty($crumbs)) { ?> 
        <div class="inner-headline">
            <ul class="breadcrumb">
                <?php foreach ($crumbs as $item) { ?>
                    <?php if (isset($item)) { ?>
                        <li>
                            <?php if (!empty($item['url'])) { ?>
                                <a href="<?php echo $item['url'].$item['end'] ?>"><?php echo $item['text'] ?></a>
                            <?php } else { ?>
                                <?php  echo $item['text'] ?>
                            <?php } ?>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>


    <style>
    
/* -------  breadcrumb ----- */

ul.breadcrumb {
  padding: 10px 16px;
  list-style: none;
  background-color: #eee;
}

ul.breadcrumb li {
  display: inline;
  font-size: 14px;
}

ul.breadcrumb li+li:before {
  padding: 8px;
  color: black;
  content: "/\00a0";
}

ul.breadcrumb li a {
  color: #0275d8;
  text-decoration: none;
}

ul.breadcrumb li a:hover {
  color: #01447e;
  text-decoration: underline;
}

/* ----- END --  breadcrumb ----- */
    
    </style>