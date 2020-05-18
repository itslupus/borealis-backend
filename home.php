<?php
    require_once('include/Initializer.php');
    require_once('include/ViewManager.php');

    require_once('logic/FetchWeek.php');
    require_once('logic/FetchAccSummary.php');

    // view manager
    $vm;
    $config;

    try {
        $init = new Initializer();
        $vm = new ViewManager();

        $config = $init->get_config();
        $init->verify_session();
    } catch (InitializerConfigInvalid $e) {
        die('> invalid config');
    } catch (InitializerInvalidSession $e) {
        header('Location: /');
        die();
    }

    $courses = fetch_week($config, $_SESSION['term']);
    $summary = fetch_acc_summary($config);

    $vm->name = $_SESSION['name'];
    $vm->courses = $courses;

    $vm->summary_total = $summary->get_total_amnt();
    $vm->summary_details = $summary->get_details();

    $vm->render('home.php', true);
?>