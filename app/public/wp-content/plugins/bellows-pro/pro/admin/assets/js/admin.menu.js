(function($){

	var bellows_admin_is_initialized = false;

	//jQuery( document ).ready( function($){
	jQuery(function($) {
		initialize_bellows_admin( 'document.ready' );
	});

	//Backup
	$( window ).on( 'load', function(){
		initialize_bellows_admin( 'window.load' );
	});

	function initialize_bellows_admin( init_point ){

		if( bellows_admin_is_initialized ) return;

		bellows_admin_is_initialized = true;

		if( ( typeof console != "undefined" ) && init_point == 'window.load' ) console.log( 'Bellows admin initialized via ' + init_point );


		var $current_menu_item = null;
		var current_menu_item_id = '';	//menu-item-x
		var $current_panel = null;

		var $settingswrap = $( '.bellows-menu-item-settings-wrapper' );

		//remove loading notice
		$( '.bellows-js-check' ).remove();

		//handle adding the "Bellows" button on each menu item upon first interaction
		$( '#menu-management' ).on( 'mouseenter touchEnd MSPointerUp pointerup' , '.menu-item:not(.bellows-processed)' , function(e){
			$(this).addClass( 'bellows-processed' );
			$(this).find( '.item-title' ).append( '<span class="bellows-settings-toggle" data-bellows-toggle="' + $(this).attr('id') + '"><i class="fa fa-gear"></i> Bellows <span class="bellows-unsaved-alert"><i class="fa fa-warning"></i> <span class="bellows-unsaved-alert-message">Unsaved</span></span></span>' );
		});

		//Don't allow clicks to propagate when clicking the toggle button, to avoid drag-starts of the menu item
		$( '#menu-management' ).on( 'mousedown' , '.bellows-settings-toggle' , function( e ){
			e.preventDefault();
			e.stopPropagation();

			return false;
		});

		var $uberwrap = $( '.ubermenu-menu-item-settings-wrapper' );
		var ubermenu = $uberwrap.length > 0 ? true : false;

		//Close UberMenu and ShiftNav when Bellows is opened
		if( ubermenu || shiftnav ){
			$( '#menu-management' ).on( 'click' , '.bellows-settings-toggle' , function( e ){
				if( ubermenu ){
					$( '.ubermenu-menu-item-settings-open' ).removeClass( 'ubermenu-menu-item-settings-open' );
					$( 'body' ).removeClass( 'ubermenu-settings-panel-is-open' );
				}
				if( shiftnav ){
					$( '.shiftnav-menu-item-settings-open' ).removeClass( 'shiftnav-menu-item-settings-open' );
					$( 'body' ).removeClass( 'shiftnav-settings-panel-is-open' );
				}
			});
		}
		//Close Bellows when UberMenu is opened
		if( ubermenu ){
			$( '#menu-management' ).on( 'click' , '.ubermenu-settings-toggle' , function( e ){
				$settingswrap.removeClass( 'bellows-menu-item-settings-open' );
				$( 'body' ).removeClass( 'bellows-settings-panel-is-open' );
			});
		}

		var $shiftwrap = $( '.shiftnav-menu-item-settings-wrapper' );
		var shiftnav = $shiftwrap.length > 0 ? true : false;

		//Close Bellows when ShiftNav is opened
		if( shiftnav ){
			$( '#menu-management' ).on( 'click' , '.shiftnav-settings-toggle' , function( e ){
				$settingswrap.removeClass( 'bellows-menu-item-settings-open' );
				$( 'body' ).removeClass( 'bellows-settings-panel-is-open' );
			});
		}

		//Handle clicking the "Bellows" button on each menu item - open settings
		$( '#menu-management' ).on( 'click' , '.bellows-settings-toggle' , function( e ){
			
			var this_menu_item_id = $(this).attr( 'data-bellows-toggle' );
			var this_menu_item_id_num = this_menu_item_id.substr(10);
			
			$current_menu_item = $(this).parents( 'li.menu-item' );

			//This is already the current item
			if( this_menu_item_id == current_menu_item_id ){
				$settingswrap.toggleClass( 'bellows-menu-item-settings-open' );
				$( 'body' ).toggleClass( 'bellows-settings-panel-is-open' );
			}
			//Switching to a different item
			else{
				$settingswrap.addClass( 'bellows-menu-item-settings-open' );
				$( 'body' ).addClass( 'bellows-settings-panel-is-open' );
				//$( '.bellows-menu-item-tab' ).click();
				//Update
				
				$current_panel = $settingswrap.find( '.bellows-menu-item-panel-' + this_menu_item_id );
				
				//Create Panel if it doesn't exist
				if( $current_panel.size() === 0 ){
					$current_panel = $( '.bellows-menu-item-panel-negative' ).clone();
					$current_panel.removeClass( 'bellows-menu-item-panel-negative' );
					$current_panel.addClass( 'bellows-menu-item-panel-' + this_menu_item_id );
					$current_panel.attr( 'data-menu-item-target-id' , this_menu_item_id_num );
				
					var hash = '#' + this_menu_item_id;

					$current_panel.find( '.bellows-menu-item-title' ).text( $current_menu_item.find('.menu-item-title').text() );
					$current_panel.find( '.bellows-menu-item-id' ).html( '<a href="'+hash+'">'+hash+'</a>' );
					$current_panel.find( '.bellows-menu-item-type' ).text( $current_menu_item.find('.item-type').text() );
					var item_data = bellows_menu_item_data[this_menu_item_id_num];
					if( item_data ){
					
						$current_panel.find( '[data-bellows-setting]' ).each( function(){
							var _data_name = $(this).data( 'bellows-setting' );

							//if( item_data[_data_name] ){
							if( item_data.hasOwnProperty( _data_name ) ){
								switch( $(this).attr('type') ){
								
									case 'checkbox':
										if( item_data[_data_name] == 'on' ){
											$(this).prop( 'checked' , true );
										}
										break;

									case 'radio':
										if( $(this).val() == item_data[_data_name] ){
											$(this).prop( 'checked' , true );
											$(this).closest( '.bellows-menu-item-setting-input-wrap' ).find( '.bellows-radio-label' ).removeClass( 'bellows-radio-label-selected' );
											$(this).closest( '.bellows-radio-label' ).addClass( 'bellows-radio-label-selected' );
										}
										break;

									case 'text':
										
										$(this).val( item_data[_data_name] );	//the basics;

										//Now check for specific types of settings that use text inputs
										
										//Media
										if( $(this).hasClass( 'bellows-media-id' ) ){
	//console.log( _data_name + '  '+ item_data[_data_name] );
	//if( _data_name == 'item_image' ) console.log( $(this) );
											if( item_data.hasOwnProperty( _data_name+'_url' ) ){
												console.log( 'media: ' + _data_name );
												$wrap = $(this).closest( '.bellows-media-wrapper' );
												$wrap.find( '.media-preview-wrap' )
													.html( '<img src="'+item_data[_data_name+'_url']+'" />' );
												$wrap.find('.bellows-edit-media-button' ).css( 'display' , 'block' )
													.attr( 'href' , item_data[_data_name+'_edit'] );

											}
										}

										break;

									default:
										$(this).val( item_data[_data_name] );
								}
							}

							switch( _data_name ){

								case 'icon':
									var $icon_wrap = $( this ).parents( '.bellows-icon-settings-wrap' );
									//console.log( item_data.icon );
									$icon_wrap.find( '.bellows-icon-selected i' ).attr( 'class' , item_data.icon );
									break;

							}
						});

						//for( _setting in item_data ){
							//console.log( _setting + ' :: ' + item_data[_setting] );
						//}
					
					}

					//Setup colorpickers
					$current_panel.find( '.bellows-colorpicker' ).wpColorPicker();

					$current_panel.find( '.bellows-menu-item-tab-content' ).hide();

					$current_panel.on( 'click' , '.bellows-menu-item-tab a' , function( e ){
						e.preventDefault();
						e.stopPropagation();
	//console.log( $(this).data('bellows-tab') );
	//console.log( $current_panel.find( '[data-bellows-tab-content="'+$(this).data('bellows-tab') + '"]' ).size() );
	//
						$current_panel.find( '.bellows-menu-item-tab > a' ).removeClass( 'bellows-menu-item-tab-current' );
						$(this).addClass( 'bellows-menu-item-tab-current' );
						$current_panel.find( '.bellows-menu-item-tab-content' ).slideUp();
						$current_panel.find( '[data-bellows-tab-content="'+$(this).data('bellows-tab') + '"]' ).slideDown();

						return false;
					});

					$current_panel.find( '.bellows-menu-item-tab > a' ).first().trigger('click');

					$settingswrap.append( $current_panel );
				}

				//Hide all other panels
				$settingswrap.find( '.bellows-menu-item-panel' ).hide();
				$current_panel.fadeIn();


				
			}

			current_menu_item_id = this_menu_item_id;

			return false;
		});

		//When a setting is changed, set the flag on the settings panel and on the menu item itself
		$settingswrap.on( 'change' , '.bellows-menu-item-setting-input' , function( e ){

			//Flag Settings Panel
			var $form = $(this).parents( 'form.bellows-menu-item-settings-form' );
			$form.find( '.bellows-menu-item-status' ).attr( 'class' , 'bellows-menu-item-status bellows-menu-item-status-warning' );
			$form.find( '.bellows-status-message' ).html( 'Settings have changed.  Click <strong>Save Menu Item</strong> to preserve these changes.' );

			//Flag Menu Item
			var item_id = $form.parents( '.bellows-menu-item-panel' ).data( 'menu-item-target-id' );
			$( '#menu-item-' + item_id ).addClass( 'bellows-unsaved' );
		});

		//Highlight radio selections
		$settingswrap.on( 'click' , '.bellows-radio-label' , function(){
			$(this).closest( '.bellows-menu-item-setting-input-wrap' ).find( '.bellows-radio-label' ).removeClass( 'bellows-radio-label-selected' );
			$(this).addClass( 'bellows-radio-label-selected' );
		});


		//Save Settings Button
		$settingswrap.on( 'click' , '.bellows-menu-item-save-button', function( e ){
			e.preventDefault();
			e.stopPropagation();

			var $form = $(this).parents('form.bellows-menu-item-settings-form' );
			var serialized = $form.serialize();
			console.log( 'serial: ' + serialized );

			//return;
			
			var data = {
				action: 'bellows_save_menu_item',
				settings: serialized,
				menu_item_id: current_menu_item_id,
				bellows_nonce: bellows_meta.nonce
			};

			$formStatus = $form.find( '.bellows-menu-item-status' );
			$formStatusMessage = $form.find( '.bellows-status-message' );
			$formStatus.attr( 'class', 'bellows-menu-item-status bellows-menu-item-status-working' );
			$formStatusMessage.text( 'Processing save request...' );

			$.post( bellows_meta.ajax_url, data, function( response ) {
				//console.log('Got this from the server: ' , response );
				if( response == -1 ){
					$formStatus.attr( 'class', 'bellows-menu-item-status bellows-menu-item-status-error' );
					$formStatusMessage.html( '<strong>Error encountered.  Settings could not be saved.</strong>  Your login/nonce may have expired.  Please try refreshing the page.');
					//console.log( response );
				}
				else{
					//$( '.bellows-menu-item-panel-' + response.menu_item_id )
					$formStatus.attr( 'class', 'bellows-menu-item-status bellows-menu-item-status-success' );
					$formStatusMessage.text( 'Settings Saved' );
					bellows_meta.nonce = response.nonce;	//update nonce

					//Remove flag on menu item
					var item_id = $form.parents( '.bellows-menu-item-panel' ).data( 'menu-item-target-id' );
					$( '#menu-item-' + item_id ).removeClass( 'bellows-unsaved' );
				}

			}, 'json' ).fail( function( d ){
				$formStatus.attr( 'class', 'bellows-menu-item-status bellows-menu-item-status-error' );
				$formStatusMessage.html( '<strong>Error encountered.  Settings could not be saved.</strong>  Response Text: <br/><textarea>' + d.responseText + '</textarea>');
				//console.log( d.responseText );
				//console.log( d );
			});

			return false;
		});

		//Close Settings Button
		$settingswrap.on( 'click' , '.bellows-menu-item-settings-close' , function( e ){
			e.preventDefault();
			e.stopPropagation();

			$settingswrap.removeClass( 'bellows-menu-item-settings-open' );
			$( 'body' ).removeClass( 'bellows-settings-panel-is-open' );
		});

		//Scroll to the menu item when the ID is clicked
		$settingswrap.on( 'click' , '.bellows-menu-item-id a' , function( e ){
			var $item = $( $(this).attr( 'href' ) );
			//console.log( $item.offset() );
			var y = $item.offset().top - 50;
			$('html, body').animate({scrollTop:y}, 'normal');
			return false;
		});

		//Show Icon Selection panel when icon is clicked
		$settingswrap.on( 'click' , '.bellows-icon-selected' , function( e ){
			$icon_set = $( this ).parents( '.bellows-icon-settings-wrap' );
			$icon_set.find( '.bellows-icons' ).fadeToggle();
			$icon_set.find( '.bellows-icons-search' ).focus();
		});


		$settingswrap.on( 'click' , '.bellows-icon-settings-wrap .bellows-icon-wrap' , function( e ){
			$icon = $( this ).find( '.bellows-icon' );
			$icon_set = $( this ).parents( '.bellows-icon-settings-wrap' );
			console.log( $icon.attr( 'class' ) + ' | ' + $icon.data( 'bellows-icon' )  );
			$icon_set.find( '.bellows-icon-selected i' ).attr( 'class' , $icon.attr( 'class' ) );
			$icon_set.find( 'select' ).val( $icon.data( 'bellows-icon' ) ).change();
			$( this ).parents( '.bellows-icons' ).fadeOut();
		});

		/* Filter Icons */
		$settingswrap.on( 'keyup' , '.bellows-icons-search' , function( e ){
			$icon_set = $( this ).parents( '.bellows-icon-settings-wrap' ).find( '.bellows-icon-wrap' );
			var val = $(this).val();
			if( val == '' ){
				$icon_set.show();
			}
			else{
				$icon_set.filter( ':not( [data-bellows-search-terms*=' +val+ '] )' ).hide();
				console.log( 'not( [data-bellows-search-terms*=' +$(this).val().toLowerCase()+ '] )' );
			}
		});




		/* Image Selection */
		var file_frame;
		var $wrap;

		$settingswrap.on( 'click', '.bellows-media-wrapper .bellows-setting-button' , function( event ){

			$wrap = $( this ).closest( '.bellows-media-wrapper' );
			event.preventDefault();
					 
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
					 
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: jQuery( this ).data( 'uploader-title' ),
				button: {
					text: jQuery( this ).data( 'uploader-button-text' ),
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();

				// Do something with attachment.id and/or attachment.url here
				$wrap.find( '.media-preview-wrap' ).html( '<img src="' + attachment.url + '"/>' );
				$wrap.find( 'input.bellows-menu-item-setting-input' ).val( attachment.id ).trigger('change');

				//$wrap.find( '.ubermenu-edit-media-button' ).attr( 'href' , attachment.id );
				//wp-admin/post.php?post=274&action=edit
			});
					 
			// Finally, open the modal
			file_frame.open();
		});

		$settingswrap.on( 'click', '.bellows-media-wrapper .bellows-remove-button' , function(e){
			var $wrap = $( this ).parents( '.bellows-media-wrapper' );
			$wrap.find( '.media-preview-wrap' ).html( '' );
			$wrap.find( 'input.bellows-menu-item-setting-input' ).val('').trigger('change');
			return false;
		});






		/* Reset Settings */
		$settingswrap.on( 'click', '.bellows-clear-settings' , function( e ){

			//Find the settings panel
			$item_settings = $( this ).parents( '.bellows-menu-item-panel' );

			//Get the menu item ID
			var menu_item_id = $item_settings.data( 'menu-item-target-id' );

			//Remove the data that the panel settings are loaded from
			delete bellows_menu_item_data[ menu_item_id ];

			//Remove the settings panel from the DOM
			$item_settings.remove();

			//Close the settings panel
			$settingswrap.removeClass( 'bellows-menu-item-settings-open' );
			$( 'body' ).removeClass( 'bellows-settings-panel-is-open' );
			
			//Reset the current menu item ID
			current_menu_item_id = false;

			//Re-open the settings
			$( '#menu-item-' + menu_item_id ).find( '.bellows-settings-toggle' ).trigger('click');

			//Flag as unsaved
			var $panel = $( '.bellows-menu-item-panel-menu-item-' + menu_item_id );
			var $form = $panel.find( 'form.bellows-menu-item-settings-form' );
			$form.find( '.bellows-menu-item-status' ).attr( 'class' , 'bellows-menu-item-status bellows-menu-item-status-warning' );
			$form.find( '.bellows-status-message' ).html( 'Settings have been cleared.  Click <strong>Save Menu Item</strong> to complete setting reset.' );

			//Flag Menu Item
			$( '#menu-item-' + menu_item_id ).addClass( 'bellows-unsaved' );


		});

	}
})(jQuery);