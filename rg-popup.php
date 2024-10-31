<?php
/**
 * @package rg_popup
 * @version 1.0.0
 */
/*
Plugin Name: RG Popup
Plugin URI: http://wordpress.org/plugins/rg_popup/
Description: Simple popup plugin
Author: RianGraphics
Version: 1.0.0
Author URI: http://riangraphics.com/
*/

add_action( 'admin_menu', 'rg_popup_menu' );

function rg_popup_menu() {
	add_menu_page( 'Popup', 'Popup', 'manage_options', 'Popup-page.php', 'rg_popup_page', 'dashicons-editor-expand', 6  );
add_action( 'admin_init', 'rg_popup_settings' );

}


function rg_popup_settings() {
	//register our settings
        register_setting( 'rg-popup-settings-group', 'rg_popup_enable' );
        register_setting( 'rg-popup-settings-group', 'rg_popup_cookie' );
        register_setting( 'rg-popup-settings-group', 'rg_popup_title' );
        register_setting( 'rg-popup-settings-group', 'rg_popup_content' );
        register_setting( 'rg-popup-settings-group', 'rg_popup_style' );
        register_setting( 'rg-popup-settings-group', 'rg_popup_from' );
        register_setting( 'rg-popup-settings-group', 'rg_popup_to' );
        register_setting( 'rg-popup-settings-group', 'rg_popup_exclude' );

}

function rg_popup_page() {
?>
<div class="wrap">
		<h2>Popup Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'rg-popup-settings-group' ); ?>
    <?php do_settings_sections( 'rg-popup-settings-group' ); ?>
	<?php //echo get_option( 'rg_popup_enable' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">On/Off</th>
        <td><input name="rg_popup_enable" type="checkbox" value="1" <?php checked( '1', get_option( 'rg_popup_enable' ) ); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Show once per session On/Off</th>
        <td><input name="rg_popup_cookie" type="checkbox" value="1" <?php checked( '1', get_option( 'rg_popup_cookie' ) ); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Title</th>
        <td><input type="text" name="rg_popup_title" value="<?php echo esc_attr( get_option('rg_popup_title', 'Popup Title') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Content</th>
        <td>
            <?php wp_editor( get_option('rg_popup_content', 'Popup Content'), 'rg_popup_content' ); ?>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row">Popup style</th>
        <td>
<select name="rg_popup_style">
        <option <?php if(get_option('rg_popup_style') == '0') { echo 'selected';} ?> value="0">Style 1</option>
        <option <?php if(get_option('rg_popup_style') == '1') { echo 'selected';} ?> value="1">Style 2</option>
        <option <?php if(get_option('rg_popup_style') == '2') { echo 'selected';} ?> value="2">Style 3</option>
       </select>
</td>
        </tr>

        <tr valign="top">
        <th scope="row">Show popup from:</th>
        <td>
            <input type="date" name="rg_popup_from" value="<?php echo esc_attr( get_option('rg_popup_from') ); ?>" />
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Show popup to:</th>
        <td>
            <input type="date" name="rg_popup_to" value="<?php echo esc_attr( get_option('rg_popup_to') ); ?>" />
        </td>
        </tr>

        <tr valign="top">
        <th scope="row">Exclude pages/posts, comma separated for multiple values</th>
        <td><input type="text" name="rg_popup_exclude" value="<?php echo esc_attr( get_option('rg_popup_exclude', '') ); ?>" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php }


function rg_popup() {

    if(!is_admin() && get_option( 'rg_popup_enable' ) === '1') {

        $pageorpostid = get_option( 'rg_popup_exclude' );
        if(!empty($pageorpostid)) {
        	$truepgid = explode(',',$pageorpostid);
        } else {
        	$truepgid = "";
        }

        if(!empty($pageorpostid)) {
        	if(!is_page($truepgid) && !is_single($truepgid)) {
        	    //show on all pages except the ones in the array
        	    ?>
                <!-- The Modal -->
                <div id="rg_modal" class="rg-modal">
                  <!-- Modal content -->
                  <div class="rg-modal-content">
                    <div class="rg-modal-header">
                      <span class="rg-close">&times;</span>
                      <?php if(!empty(get_option( 'rg_popup_title' ))) { ?>
                      <h4><?php echo get_option( 'rg_popup_title' ); ?></h4>
                      <?php } ?>
                    </div>
                    <div class="rg-modal-body">
                    <?php
                    if(!empty(get_option( 'rg_popup_content' ))) {
                       echo do_shortcode(get_option( 'rg_popup_content' ));
                        }
                    ?>
                    </div>
                  </div>
                </div>
                <style>

                <?php
                switch(get_option( 'rg_popup_style')) {
                    case 0:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 100px; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      background-color: #fefefe;
                      position:relative;
                      margin: auto;
                      padding: 20px;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;

                    case 1:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 0; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      background-color: #fefefe;
                      position:relative;
                      margin: auto;
                      padding: 0;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;

                    case 2:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 100px; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      background-color: #fefefe;
                      position:relative;
                      margin: auto;
                      padding: 20px;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;

                    default:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 100px; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      background-color: #fefefe;
                      position:relative;
                      margin: auto;
                      padding: 20px;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;
                } ?>

                    .rg-modal-content img {
                        max-width:100%;
                        margin:0!important;
                    }

                    /* The Close Button */
                    .rg-close {
                      color: #aaaaaa;
                      float: right;
                      font-size: 28px;
                      font-weight: bold;
                      position: absolute;
                      top: 5px;
                      right: 5px;
                    }

                    .rg-close:hover,
                    .rg-close:focus {
                      color: #000;
                      text-decoration: none;
                      cursor: pointer;
                    }
                </style>
                <script>
                let rg_modal = document.getElementById("rg_modal");
                // Get the <span> element that closes the modal
                let rg_span = document.getElementsByClassName("rg-close")[0];

                // When the user clicks on <span> (x), close the modal
                rg_span.onclick = function() {
                  rg_modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                  if (event.target == rg_modal) {
                    rg_modal.style.display = "none";
                  }
                }
                function showrRGPopup() {
                    <?php if(get_option( 'rg_popup_cookie' ) === '1') { ?>
                    let poped = "rg-popme";
                    let readValue = sessionStorage['rg-poped'];
                    if(poped !== readValue) {
                      rg_modal.style.display = "flex";
                    }
                    sessionStorage['rg-poped'] = poped;
                    <?php } else { ?>
                      rg_modal.style.display = "flex";
                    <?php } ?>
                }
                <?php if(!empty(get_option( 'rg_popup_from' )) && !empty(get_option( 'rg_popup_to' ))) { ?>
                window.setTimeout(function(){
                let current = new Date();
                let expiry = new Date("<?php echo get_option( 'rg_popup_from' ); ?>");
                let nexpiry = new Date("<?php echo get_option( 'rg_popup_to' ); ?>");
                if(current.getTime() >= expiry.getTime() && current.getTime() <= nexpiry.getTime()) {

                    showrRGPopup();

                }}, 1);
                <?php } else { ?>

                showrRGPopup();

                <?php } ?>
                </script>

              <?php
        	}
          } else {
              // show on all pages
              ?>
              <!-- The Modal -->
                <div id="rg_modal" class="rg-modal">
                  <!-- Modal content -->
                  <div class="rg-modal-content">
                    <div class="rg-modal-header">
                      <span class="rg-close">&times;</span>
                      <?php if(!empty(get_option( 'rg_popup_title' ))) { ?>
                      <h4><?php echo get_option( 'rg_popup_title' ); ?></h4>
                      <?php } ?>
                    </div>
                    <div class="rg-modal-body">
                    <?php
                    if(!empty(get_option( 'rg_popup_content' ))) {
                       echo do_shortcode(get_option( 'rg_popup_content' ));
                        }
                    ?>
                    </div>
                  </div>
                </div>
                <style>

                   <?php
                switch(get_option( 'rg_popup_style')) {
                    case 0:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 100px; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      position:relative;
                      background-color: #fefefe;
                      margin: auto;
                      padding: 20px;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;

                    case 1:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 0; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      background-color: #fefefe;
                      position:relative;
                      margin: auto;
                      padding: 0;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;

                    case 2:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 100px; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      background-color: #fefefe;
                      position:relative;
                      margin: auto;
                      padding: 20px;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;

                    default:
                ?>

                    /* The Modal (background) */
                    .rg-modal {
                      display: none; /* Hidden by default */
                      position: fixed; /* Stay in place */
                      z-index: 99999; /* Sit on top */
                      padding-top: 100px; /* Location of the box */
                      left: 0;
                      top: 0;
                      width: 100%; /* Full width */
                      height: 100%; /* Full height */
                      overflow: auto; /* Enable scroll if needed */
                      background-color: rgb(0,0,0); /* Fallback color */
                      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                      align-items: center;
                      justify-content: center;
                    }

                    /* Modal Content */
                    .rg-modal-content {
                      background-color: #fefefe;
                      position:relative;
                      margin: auto;
                      padding: 20px;
                      border: 1px solid #888;
                      width: 60%;
                    }

                    <?php
                    break;
                } ?>

                    .rg-modal-content img {
                        max-width:100%;
                        margin:0!important;
                    }

                    /* The Close Button */
                    .rg-close {
                      color: #aaaaaa;
                      float: right;
                      font-size: 28px;
                      font-weight: bold;
                      position: absolute;
                      top: 5px;
                      right: 5px;
                    }

                    .rg-close:hover,
                    .rg-close:focus {
                      color: #000;
                      text-decoration: none;
                      cursor: pointer;
                    }
                </style>
                <script>
                let rg_modal = document.getElementById("rg_modal");
                // Get the <span> element that closes the modal
                let rg_span = document.getElementsByClassName("rg-close")[0];

                // When the user clicks on <span> (x), close the modal
                rg_span.onclick = function() {
                  rg_modal.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                  if (event.target == rg_modal) {
                    rg_modal.style.display = "none";
                  }
                }
                function showrRGPopup() {
                    <?php if(get_option( 'rg_popup_cookie' ) === '1') { ?>
                    let poped = "rg-popme";
                    let readValue = sessionStorage['rg-poped'];
                    if(poped !== readValue) {
                      rg_modal.style.display = "flex";
                    }
                    sessionStorage['rg-poped'] = poped;
                    <?php } else { ?>
                      rg_modal.style.display = "flex";
                    <?php } ?>
                }
                <?php if(!empty(get_option( 'rg_popup_from' )) && !empty(get_option( 'rg_popup_to' ))) { ?>
                window.setTimeout(function(){
                let current = new Date();
                let expiry = new Date("<?php echo get_option( 'rg_popup_from' ); ?>");
                let nexpiry = new Date("<?php echo get_option( 'rg_popup_to' ); ?>");
                if(current.getTime() >= expiry.getTime() && current.getTime() <= nexpiry.getTime()) {

                    showrRGPopup();

                }}, 1);
                <?php } else { ?>

                showrRGPopup();

                <?php } ?>
                </script>
                <?php

         }
    }

}

add_action( 'wp_footer', 'rg_popup' );
