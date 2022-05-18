
<?php 
if(isset($_SESSION['message'])){
    if(isset($_SESSION['message']['error'])){
?>
    <div class="error">
        <p><?php echo $_SESSION['message']['error']?></p>
    </div>   
    <?php };
    if(isset($_SESSION['message']['success'])){?>
    <div class="success">
        <p><?php echo $_SESSION['message']['success']?></p>
    </div>   
<?php 
    }
unset($_SESSION['message']);
}
?>