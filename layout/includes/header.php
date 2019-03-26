<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details
 *
 * @package   theme_adaptable
 * @copyright 2015 Jeremy Hopkins (Coventry University)
 * @copyright 2015-2017 Fernando Acedo (3-bits.com)
 * @copyright 2017-2018 Manoj Solanki (Coventry University)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();

// Set HTTPS if needed.
if (empty($CFG->loginhttps)) {
    $wwwroot = $CFG->wwwroot;
} else {
    $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
}

// Check if this is a course or module page and check setting to hide site title.
// If not one of these pages, by default show it (set $hidesitetitle to false).
if ( (strstr($PAGE->pagetype, 'course')) ||
     (strstr($PAGE->pagetype, 'mod')) && ($this->page->course->id > 1) ) {
    $hidesitetitle = !empty(($PAGE->theme->settings->coursepageheaderhidesitetitle)) ? true : false;
} else {
    $hidesitetitle = false;
}

// Screen size.
theme_adaptable_initialise_zoom($PAGE);
$setzoom = theme_adaptable_get_zoom();

theme_adaptable_initialise_full($PAGE);
$setfull = theme_adaptable_get_full();

// Navbar.
if (isset($PAGE->theme->settings->stickynavbar) && $PAGE->theme->settings->stickynavbar == 1
    && $PAGE->pagetype != "grade-report-grader-index" && $PAGE->bodyid != "page-grade-report-grader-index") {
    $fixedheader = true;
} else {
    $fixedheader = false;
}

$PAGE->requires->js_call_amd('theme_adaptable/bsoptions', 'init', array($fixedheader));
$PAGE->requires->js_call_amd('theme_boost/drawer', 'init');

// Layout.
$left = (!right_to_left());  // To know if to add 'pull-right' and 'desktop-first-column' classes in the layout for LTR.

$hasmiddle = $PAGE->blocks->region_has_content('middle', $OUTPUT);
$hasfootnote = (!empty($PAGE->theme->settings->footnote));

$hideheadermobile = $PAGE->theme->settings->hideheadermobile;
$hidealertsmobile = $PAGE->theme->settings->hidealertsmobile;
$hidesocialmobile = $PAGE->theme->settings->hidesocialmobile;


// Load header background image if exists.
$headerbg = '';

if (!empty($PAGE->theme->settings->headerbgimage)) {
    $headerbg = ' style="background-image: url('.$PAGE->theme->setting_file_url('headerbgimage', 'headerbgimage').');
                         background-position: 0 0; background-repeat: no-repeat; background-size: cover;"';
}

// Select fonts used.
$fontname = '';
$fontheadername = '';
$fonttitlename = '';
$fontweight = '';
$fontheaderweight = '';
$fonttitleweight = '';
$fontssubset = '';

switch ($PAGE->theme->settings->fontname) {
    case 'default':
        // Get the default font used by the browser.
    break;

    default:
        // Get the Google fonts.
        $fontname = str_replace(" ", "+", $PAGE->theme->settings->fontname);
        $fontheadername = str_replace(" ", "+", $PAGE->theme->settings->fontheadername);
        $fonttitlename = str_replace(" ", "+", $PAGE->theme->settings->fonttitlename);

        $fontweight = ':400,400i';
        $fontheaderweight = ':400,400i';
        $fonttitleweight = ':700,700i';
        $fontssubset = '';

        // Get the Google Font weights.
        $fontweight = ':'.$PAGE->theme->settings->fontweight.','.$PAGE->theme->settings->fontweight.'i';
        $fontheaderweight = ':'.$PAGE->theme->settings->fontheaderweight.','.$PAGE->theme->settings->fontheaderweight.'i';
        $fonttitleweight = ':'.$PAGE->theme->settings->fonttitleweight.','.$PAGE->theme->settings->fonttitleweight.'i';

        // Get the Google fonts subset.
        if (!empty($PAGE->theme->settings->fontsubset)) {
            $fontssubset = '&subset='.$PAGE->theme->settings->fontsubset;
        } else {
            $fontssubset = '';
        }
    break;
}

// Get the HTML for the settings bits.
$html = theme_adaptable_get_html_for_settings($OUTPUT, $PAGE);

if (right_to_left()) {
    $regionbsid = 'region-bs-main-and-post';
} else {
    $regionbsid = 'region-bs-main-and-pre';
}

// Social icons class.
$showicons = "";
$showicons = $PAGE->theme->settings->blockicons;
if ($showicons == 1) {
    $showiconsclass = "showblockicons";
} else {
    $showiconsclass = " ";
}

// Setting for default screen view. Does not override user's preference.
$defaultview = "";
$defaultview = $PAGE->theme->settings->viewselect;
if ($defaultview == 1 && $setfull == "") {
    $setfull = "fullin";
}

// HTML header.
echo $OUTPUT->doctype();
?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="icon" href="<?php echo $OUTPUT->favicon(); ?>" />

<?php
// HTML head.
echo $OUTPUT->standard_head_html() ?>
    <!-- CSS print media -->
    <link rel="stylesheet" type="text/css" href="<?php echo $wwwroot; ?>/theme/adaptable/style/print.css" media="print">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Twitter Card data -->
    <meta name="twitter:card" value="summary">
    <meta name="twitter:site" value="<?php echo $SITE->fullname; ?>" />
    <meta name="twitter:title" value="<?php echo $OUTPUT->page_title(); ?>" />

    <!-- Open Graph data -->
    <meta property="og:title" content="<?php echo $OUTPUT->page_title(); ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $wwwroot; ?>" />
    <meta name="og:site_name" value="<?php echo $SITE->fullname; ?>" />

    <!-- Chrome, Firefox OS and Opera on Android topbar color -->
    <meta name="theme-color" content="<?php echo $PAGE->theme->settings->maincolor; ?>" />

    <!-- Windows Phone topbar color -->
    <meta name="msapplication-navbutton-color" content="<?php echo $PAGE->theme->settings->maincolor; ?>" />

    <!-- iOS Safari topbar color -->
    <meta name="apple-mobile-web-app-status-bar-style" content="<?php echo $PAGE->theme->settings->maincolor; ?>" />

    <?php
    // Load fonts.
    if ((!empty($fontname)) && ($fontname != 'default') && ($fontname != 'custom')) {
        ?>
    <!-- Load Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=<?php echo $fontname.$fontweight.$fontssubset; ?>'
    rel='stylesheet'
    type='text/css'>
    <?php
    }
    ?>

    <?php
    if ((!empty($fontheadername)) && ($fontheadername != 'default') && ($fontname != 'custom')) {
    ?>
        <link href='https://fonts.googleapis.com/css?family=<?php echo $fontheadername.$fontheaderweight.$fontssubset; ?>'
        rel='stylesheet'
        type='text/css'>
    <?php
    }
    ?>

    <?php
    if ((!empty($fonttitlename)) && ($fonttitlename != 'default') && ($fontname != 'custom')) {
    ?>
        <link href='https://fonts.googleapis.com/css?family=<?php echo $fonttitlename.$fonttitleweight.$fontssubset; ?>'
        rel='stylesheet'
        type='text/css'>
    <?php
    }
    ?>
</head>

<body <?php echo $OUTPUT->body_attributes(array('two-column', $setzoom)); ?>>

<?php
echo $OUTPUT->standard_top_of_body_html();

// Development or wrong moodle version alert.
echo $OUTPUT->get_dev_alert();
?>

<div id="page" class="container-fluid <?php echo "$setfull $showiconsclass"; ?>">

<?php
    // If the device is a mobile and the alerts are not hidden or it is a desktop then load and show the alerts.
if (((theme_adaptable_is_mobile()) && ($hidealertsmobile == 1)) || (theme_adaptable_is_desktop())) {
    // Display alerts.
    echo $OUTPUT->get_alert_messages();
}

// Background image in Header.
?>

<header id="adaptable-page-header-wrapper" <?php echo $headerbg; ?> >

<div id="above-header" class="mb-2">

    <nav class="navbar navbar-expand btco-hover-menu mr-2 mr-md-4">

             <div data-region="drawer-toggle" class="d-lg-none mr-3">
                <button aria-expanded="false" aria-controls="nav-drawer" type="button" class="btn nav-link float-sm-left mr-1"
                 data-action="toggle-drawer" data-side="left" data-preference="drawer-open-nav">
                 <i class="icon fa fa-bars fa-fw " aria-hidden="true"></i><span class="sr-only">Side panel</span>
                 </button>
            </div>

        <div class="collapse navbar-collapse">

	        <?php
	           if (empty($PAGE->theme->settings->menuslinkright)) {
	                echo $OUTPUT->get_top_menus();
	           }
            ?>

            <ul class="navbar-nav ml-auto">

                <div class="pull-left">
                    <?php echo $OUTPUT->user_menu(); ?>
                </div>

                <?php
                    if (!empty($PAGE->theme->settings->menuslinkright)) {
    	                echo $OUTPUT->get_top_menus();
                    }
                ?>

                <?php

                // Add messages / notifications (moodle 3.2 or higher).
                if ($CFG->version > 2016120400) {
                    // Remove Messages and Notifications icons in Quiz pages even if they don't use SEB.
                    if ($PAGE->pagetype != "mod-quiz-attempt") {
                        echo $OUTPUT->navbar_plugin_output();
                    }
                }
                ?>

						<?php
                        if (empty($PAGE->layout_options['langmenu']) || $PAGE->layout_options['langmenu']) {
                            echo '<li class="nav-item dropdown ml-3">';
                            echo $OUTPUT->lang_menu();
                            echo '</li>';
                        }
                        ?>



				<?php
                if (!isloggedin() || isguestuser()) {
                    echo $OUTPUT->page_heading_menu();

                    if ($PAGE->theme->settings->displaylogin == 'box') {
                        // Login button.
                ?>
                        <form class="form-inline my-2 my-lg-0" action="<?php p($wwwroot) ?>/login/index.php" method="post">
                            <input type="hidden" name="logintoken" value="<?php echo s(\core\session\manager::get_login_token()); ?>" />
                            <input type="text" name="username"
                                    placeholder="<?php echo get_string('loginplaceholder', 'theme_adaptable'); ?>" size="10">
                            <input type="password" name="password"
                                    placeholder="<?php echo get_string('passwordplaceholder', 'theme_adaptable'); ?>"  size="10">
                            <button class="btn-login" type="submit"><?php echo get_string('logintextbutton', 'theme_adaptable'); ?></button>
                        </form>
                <?php
                    } else if ($PAGE->theme->settings->displaylogin == 'button') {
                ?>
                        <form class="form-inline my-2 my-lg-0" action="<?php p($wwwroot) ?>/login/index.php" method="post">
                            <input type="hidden" name="logintoken" value="<?php echo s(\core\session\manager::get_login_token()); ?>" />
                            <button class="btn-login" type="submit">
                                <?php echo get_string('logintextbutton', 'theme_adaptable'); ?>
                            </button>
                        </form>
                <?php
                    }
                } else {
                    // Display user profile menu.
                    ?>

                    <li class="nav-item dropdown ml-3 ml-md-4">
                        <a class="nav-link dropdown-toggle" href="javascript:void(0);" id="navbarAboveHeaderDropdownMenuLink"
                        		data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

                        <?php
                            // Show user avatar.
                            $userpic = $OUTPUT->user_picture($USER, array('link' => false, 'size' => 80, 'class' => 'userpicture'));
                            echo $userpic;

                            // Show username based in fullnamedisplay variable.
                            echo '<span class="d-none d-md-inline-block">';
                            echo fullname($USER);
                            echo '</span>';
                            mb_internal_encoding("UTF-8");
                            echo '<span class="d-sm-inline-block d-md-none">';
                            echo mb_substr($USER->firstname, 0, 1, 'utf-8') . ' ';
                            echo mb_substr($USER->lastname, 0, 1, 'utf-8');
                            echo '</span>';
                        ?>
                            <!-- span class="fa fa-angle-down"></span -->

                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarAboveHeaderDropdownMenuLink">
                        	<?php echo $OUTPUT->user_profile_menu() ?>
                        </ul>
	                </li>

                <?php } ?>

            </ul>
        </div>
    </nav>

</div>

<?php

// If it is a mobile and the header is not hidden or it is a desktop then load and show the header.
if (((theme_adaptable_is_mobile()) && ($hideheadermobile == 1)) || (theme_adaptable_is_desktop())) {
?>

<div id="page-header" class="d-none d-md-block container">

<?php
// Site title or logo.
if (!$hidesitetitle) {
    echo $OUTPUT->get_logo_title();
}
?>

<?php
// Remove Search Box or Social icons in Quiz pages even if they don't use SEB.
if ($PAGE->pagetype != "mod-quiz-attempt") {
    // Social icons.
    if ($PAGE->theme->settings->socialorsearch == 'social') {
        // If it is a mobile and the social icons are not hidden or it is a desktop then load and show the social icons.
        if (((theme_adaptable_is_mobile()) && ($hidesocialmobile == 1)) || (theme_adaptable_is_desktop())) {
            ?>
            <div class="socialbox pull-right">
                <?php
                echo $OUTPUT->socialicons();
                ?>
            </div>
            <?php
        }
    }
        ?>

    <?php
    // Search box.
    if ( (!$hidesitetitle) && ($PAGE->theme->settings->socialorsearch == 'search') ) { ?>
        <div class="searchbox">
            <form action="<?php echo $wwwroot; ?>/course/search.php">
                <label class="hidden" for="search-1" style="display: none;"><?php echo get_string("searchcourses")?></label>
                <div class="search-box grey-box bg-white clear-fix">
                    <input placeholder="<?php echo get_string("searchcourses", "theme_adaptable"); ?>"
                            accesskey="6"
                            class="search_tour bg-white no-border left search-box__input ui-autocomplete-input"
                            type="text"
                            name="search"
                            id="search-1"
                            autocomplete="off">
                            <button title="<?php echo get_string("searchcourses", "theme_adaptable")?>"
                                    type="submit" class="no-border bg-white pas search-box__button">
                                    <abbr class="fa fa-search" title="<?php echo get_string("searchcourses", "theme_adaptable");?>">
                                    </abbr>
                            </button>
                </div>
            </form>
        </div>
    <?php
    }
}
    ?>

    <div id="course-header">
        <?php echo $OUTPUT->course_header(); ?>
    </div>
</div>

<?php
}
?>

<?php

// Navbar Menu.
if (
    (isloggedin() && !isguestuser()) ||
    (!empty($PAGE->theme->settings->enablenavbarwhenloggedout)) ) {

    // Remove menu navbar in Quiz pages even if they don't use SEB.
    if ($PAGE->pagetype != "mod-quiz-attempt") {
?>
    <!-- div id="navwrap">
        <div class="container">
            <div class="navbar">
                <nav role="navigation" class="navbar-inner">
                    <div class="container-fluid">
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                        <div class="nav-collapse collapse "> -->

<div id="nav-drawer" data-region="drawer" class="d-print-none moodle-has-zindex closed" aria-hidden="true" tabindex="-1">
        <nav class="list-group">

        <ul class="list-unstyled components">

            <?php echo $OUTPUT->navigation_menu('main-navigation-drawer'); ?>

            <?php
            if (empty($PAGE->theme->settings->disablecustommenu)) {
                    echo $OUTPUT->custom_menu_drawer();
            }
            ?>
            <?php
            if ($PAGE->theme->settings->enabletoolsmenus) {
                    echo $OUTPUT->tools_menu('tools-menu-drawer');
            }
            ?>


        </ul>

        </nav>

        <nav class="list-group m-t-1">
            <a class="list-group-item list-group-item-action " href="<?php echo $CFG->wwwroot . '/admin/search.php'; ?>" data-key="sitesettings" data-isexpandable="0" data-indent="0" data-showdivider="1" data-type="71" data-nodetype="1" data-collapse="0" data-forceopen="0" data-isactive="0" data-hidden="0" data-preceedwithhr="0">
                <div class="m-l-0">
                    <div class="media">
                        <span class="media-left">
                            <i class="icon fa fa-wrench fa-fw " aria-hidden="true"></i>
                        </span>
                        <span class="media-body ">Site administration</span>
                    </div>
                </div>
            </a>
        </nav>
    </div>

<div id="main-navbar" class="d-none d-lg-block">
    <nav class="navbar navbar-expand-md btco-hover-menu ">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">

    		<ul class="navbar-nav">

            <?php echo $OUTPUT->navigation_menu('main-navigation'); ?>

            <?php
            if (empty($PAGE->theme->settings->disablecustommenu)) {
                    echo $OUTPUT->custom_menu();
            }
            ?>
            <?php
            if ($PAGE->theme->settings->enabletoolsmenus) {
                    echo $OUTPUT->tools_menu();
            }
            ?>
    		</ul>
            <!-- ul class="nav pull-right" -->
			<ul class="navbar-nav ml-auto">

					<li class="nav-item mx-1">
                 		<div id="edittingbutton" class="breadcrumb-button">
							<?php echo $OUTPUT->page_heading_button(); ?>
                        </div>
                    </li>
                <?php
                if (isloggedin()) {
                    if ($PAGE->theme->settings->enableshowhideblocks) { ?>
                       <!--  li class="hbl" -->
                       <li class="nav-item mx-1 hbl">
                           <a href="javascript:void(0);" class="moodlezoom" title="<?php echo get_string('hideblocks', 'theme_adaptable') ?>">
                               <i class="fa fa-indent fa-lg"></i>
    	                       <span class="zoomdesc"><?php echo get_string('hideblocks', 'theme_adaptable') ?></span>
                           </a>
                       </li>
                       <!-- li class="sbl" -->
                       <li class="nav-item mx-1 sbl">
                           <a href="javascript:void(0);" class="moodlezoom" title="<?php echo get_string('showblocks', 'theme_adaptable') ?>">
                               <i class="fa fa-outdent fa-lg"></i>
                               <span class="zoomdesc"><?php echo get_string('showblocks', 'theme_adaptable') ?></span>
                           </a>
                       </li>
                <?php
                    }

                    if ($PAGE->theme->settings->enablezoom) { ?>
                        <li class="nav-item mx-1 hbll">
                            <a class="nav-link" href="javascript:void(0);" class="moodlewidth" title="<?php echo get_string('fullscreen', 'theme_adaptable') ?>">
                                <i class="fa fa-expand fa-lg"></i>
                                <span class="zoomdesc"><?php echo get_string('fullscreen', 'theme_adaptable') ?></span>
                            </a>
                        </li>
                        <li class="nav-item mx-1 sbll">
                            <a class="nav-link" href="javascript:void(0);" class="moodlewidth" title="<?php echo get_string('standardview', 'theme_adaptable') ?>">
                                <i class="fa fa-compress fa-lg"></i>
                                <span class="zoomdesc"><?php echo get_string('standardview', 'theme_adaptable') ?></span>
                            </a>
                        </li>
                <?php
                    }
                }
                    }
                    ?>
    		</ul>

            <!-- div id="edittingbutton" class="pull-right breadcrumb-button">
                <?php echo $OUTPUT->page_heading_button(); ?>
            </div -->
    		<!-- /div -->

    	</div>
    </nav>
</div>
            <!-- /div>
        </div>
    </div -->
<?php
}
?>

</header>

<?php

// Display News Ticker.
echo $OUTPUT->get_news_ticker();
