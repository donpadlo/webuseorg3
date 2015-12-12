<?php
echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/upload.css'>";
?>

	<link href="http://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet" type="text/css"/>

	<link href="js/jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>

	<script>
		var examples = [];
	</script>

			
				
					<div id="userpic" class="userpic">
						<div class="js-preview userpic__preview"></div>
						<div class="btn btn-success js-fileapi-wrapper">
							<div class="js-browse">
								<span class="btn-txt">Выбрать фото</span>
								<input type="file" name="filedata"/>
							</div>
							<div class="js-upload" style="display: none;">
								<div class="progress progress-success"><div class="js-progress bar"></div></div>
								<span class="btn-txt">Загружаем</span>
							</div>
						</div>
					</div>

			

				<script>
					examples.push(function (){
						$('#userpic').fileapi({
							url: 'controller/server/common/uploadfile.php',
							accept: 'image/*',
                                                        data: { 'session-id': 123 },
                                                        onFileComplete: function (evt, uiEvt){
                                                              alert(uiEvt.result.msg);
                                                        },
							imageSize: { minWidth: 200, minHeight: 200 },
							elements: {
								active: { show: '.js-upload', hide: '.js-browse' },
								preview: {
									el: '.js-preview',
									width: 200,
									height: 200
								},
								progress: '.js-progress'
							},
							onSelect: function (evt, ui){
								var file = ui.files[0];

								if( file ){
									$('#popup').modal({
										closeOnEsc: true,
										closeOnOverlayClick: false,
										onOpen: function (overlay){
											$(overlay).on('click', '.js-upload', function (){
												$.modal().close();
												$('#userpic').fileapi('upload');
											});

											$('.js-img', overlay).cropper({
												file: file,
												bgColor: '#fff',
												maxSize: [$(window).width()-100, $(window).height()-100],
												minSize: [200, 200],
												selection: '90%',
												onSelect: function (coords){
													$('#userpic').fileapi('crop', file, coords);
												}
											});
										}
									}).open();
								}
							}
						});
					});
				</script>
			




	<div id="popup" class="popup" style="display: none;">
		<div class="popup__body"><div class="js-img"></div></div>
		<div style="margin: 0 0 5px; text-align: center;">
			<div class="js-upload btn btn_browse btn_browse_small">Загрузить</div>
		</div>
	</div>



	<script>
		var FileAPI = {
			  debug: true
			, media: true
			, staticPath: './FileAPI/'
		};
	</script>
	<script src="js/FileAPI/FileAPI.min.js"></script>
	<script src="js/FileAPI/FileAPI.exif.js"></script>
	<script src="js/jquery.fileapi.js"></script>
	<script src="js/jcrop/jquery.Jcrop.min.js"></script>
	<script src="js/statics/jquery.modal.js"></script>


	<script>
		jQuery(function ($){
			var $blind = $('.splash__blind');

			$('.splash')
				.mouseenter(function (){
					$('.splash__blind', this)
						.animate({ top: -10 }, 'fast', 'easeInQuad')
						.animate({ top: 0 }, 'slow', 'easeOutBounce')
					;
				})
				.click(function (){
					$(this).off();

					if( !FileAPI.support.media ){
						$blind.animate({ top: -$(this).height() }, 'slow', 'easeOutQuart');
					}

					FileAPI.Camera.publish($('.splash__cam'), function (err, cam){
						if( err ){
							alert("Unfortunately, your browser does not support webcam.");
						} else {
							$blind.animate({ top: -$(this).height() }, 'slow', 'easeOutQuart');
						}
					});
				})
			;


			$('.example').each(function (){
				var $example = $(this);

				

				$('<div></div>')
					.append('<div data-code="javascript"><pre><code>'+ $.trim(_getCode($example.find('script'))) +'</code></pre></div>')
					.append('<div data-code="html" style="display: none"><pre><code>'+ $.trim(_getCode($example.find('.example__left'), true)) +'</code></pre></div>')
					.appendTo($example.find('.example__right'))
					.find('[data-code]').each(function (){
						/** @namespace hljs -- highlight.js */
						if( window.hljs && (!$.browser.msie || parseInt($.browser.version, 10) > 7) ){
							this.className = 'example__code language-' + $.attr(this, 'data-code');
							hljs.highlightBlock(this);
						}
					})
				;
			});


			$('body').on('click', '[data-tab]', function (evt){
				evt.preventDefault();

				var el = evt.currentTarget;
				var tab = $.attr(el, 'data-tab');
				var $example = $(el).closest('.example');

				$example
					.find('[data-tab]')
						.removeClass('active')
						.filter('[data-tab="'+tab+'"]')
							.addClass('active')
							.end()
						.end()
					.find('[data-code]')
						.hide()
						.filter('[data-code="'+tab+'"]').show()
				;
			});


			function _getCode(node, all){
				var code = FileAPI.filter($(node).prop('innerHTML').split('\n'), function (str){ return !!str; });
				if( !all ){
					code = code.slice(1, -2);
				}

				var tabSize = (code[0].match(/^\t+/) || [''])[0].length;

				return $('<div/>')
					.text($.map(code, function (line){
						return line.substr(tabSize).replace(/\t/g, '   ');
					}).join('\n'))
					.prop('innerHTML')
						.replace(/ disabled=""/g, '')
						.replace(/&amp;lt;%/g, '<%')
						.replace(/%&amp;gt;/g, '%>')
				;
			}


			// Init examples
			FileAPI.each(examples, function (fn){
				fn();
			});
		});
	</script>
