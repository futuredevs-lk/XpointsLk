function claim_reward(taskId) {
  $("#tg-loader").show();
  $.post(
    "./tg_crud/task-claim-reward.php",
    {
      claim: true,
      taskId: taskId,
    },
    function (data) {
      $("#tg-loader").hide();
      data = $.parseJSON(data);
      if (data.success) {
        congrats_panel(data.success, data.icon);
        fetchTasks();
        fetchMyAssets();
        fetchXpBal();
      } else {
        warning_panel(data.error);
      }
    }
  );
}
