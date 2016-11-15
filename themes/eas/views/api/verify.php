<?php
/**
 * Created by PhpStorm.
 * User: dkhodakovskiy
 * Date: 15.11.16
 * Time: 16:12
 *
 * @var $error ErrorHandler
 * @var $user User
 */
?>

<?php if ($error) { ?>
    <div class="alert-warning"><?php echo "{$error->code}: {$error->message}" ?></div>
<?php } else { ?>
    <div class="alert-success">OK: your character page is located
        <?php echo CHtml::link('here', Yii::app()->createUrl("/api/user/{$user->characterID}")) ?>
    </div>
<?php } ?>
