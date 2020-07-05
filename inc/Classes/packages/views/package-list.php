
<?php if(!isset($_GET['user'])){?>
<div class="wrap">
    <h2><?php _e( 'Packagelist', 'webdevs' ); ?></h2>

    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">

        <?php
        $list_table = new package_list_table();
        $list_table->prepare_items();
        $list_table->search_box( 'search', 'search_id' );
        $list_table->display();
        ?>
    </form>
</div>

<?php }else{?>

<div class="wrap">
    <h2><?php _e( 'Packagelist', 'webdevs' ); ?></h2>
	<?php  
	  $list_table = new package_list_table();
	  $user = $list_table->get_user_data($_GET['user']);
	  $UserData = $user->data;

	  //var_dump($UserData['userdata']);
	
	?>
    <form method="post">
        <input type="hidden" name="page" value="ttest_list_table">
		<div style="background-color:white;padding:20px;margin-top:10px;">
			<ul>
				<li><h3> User Information </h3></li>
				<li style="padding:10px">Name:<?=$UserData->display_name?></li>
				<li style="padding:10px">Email: <?=$UserData->user_login?></li>
				
			</ul>
			<a href="user-edit.php?user_id=80" style="border:1px solid black;padding:10px;text-decoration:none">View user</a>
		</div>
        <?php
      
        $list_table->prepare_items_user($_GET['user']);
        $list_table->search_box( 'search', 'search_id' );
        $list_table->display();
        ?>
    </form>
</div>

<?php } ?>
