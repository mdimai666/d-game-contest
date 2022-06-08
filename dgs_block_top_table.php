<?php function f_dgs_block_top_table($data){ ?>
<?php 

$posts = $data['posts'];

// __dump($posts)


?>

<section class="dgc-front-top_table <?=basename(__FILE__,'.php') ?>" style="" data-game="<?=$data['game'];?>">

    <div class="text-end text-right">
        <button class="btn btn-outline-primary dgc-front-top_table__refresh_btn"> обновить
        </button>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Пользователь</th>
                <th>Очки</th>
                <th>Дата</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $n=1;                    
                foreach ($posts as $p):

            ?>
                <tr>
                    <td><?=$n;?></td>
                    <td><?=$p->post_title;?></td>
                    <td><?=$p->e_score;?></td>
                    <td><?=$p->post_date;?></td>
                </tr>
            <?php
                $n++;
                endforeach;

            ?>
        </tbody>
    </table>
</section>
<?php  } 
add_action('dgs_block_top_table', 'f_dgs_block_top_table', 10, 1);