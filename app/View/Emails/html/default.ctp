<?php
/**
 * Default HTML Email Template
 *
 * The default HTML email template will create an inline CSS body section with the message.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Emails.html
 */
?>

<?php
$settingModel = ClassRegistry::init('Setting');
$setting = $settingModel->findById(Setting::$default);
?>

<body style="min-width: 100%;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;margin: 0;padding: 0;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;text-align: left;line-height: 19px;font-size: 14px;width: 100% !important;">
	<table
		style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: left;height: 100%;width: 100%;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;">
		<tr style="padding: 0;vertical-align: top;text-align: left;">
			<td align="center" valign="top"
				style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0;vertical-align: top;text-align: center;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;border-collapse: collapse !important;">
				<center style="width: 100%;min-width: 580px;">
					<table
						style="border-spacing: 0;border-collapse: collapse;padding: 0px;vertical-align: top;text-align: left;background: #999999;width: 100%;position: relative;">
						<tr style="padding: 0;vertical-align: top;text-align: left;">
							<td align="center"
								style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0;vertical-align: top;text-align: center;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;border-collapse: collapse !important;">
								<center style="width: 100%;min-width: 580px;">
									<table
										style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: inherit;width: 580px;margin: 0 auto;">
										<tr style="padding: 0;vertical-align: top;text-align: left;">
											<td
												style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 10px 20px 0px 0px;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;position: relative;padding-right: 0px;border-collapse: collapse !important;">
												<table
													style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: left;margin: 0 auto;width: 580px;">
													<tr style="padding: 0;vertical-align: top;text-align: left;">
														<td
															style="text-align: right;vertical-align: middle;word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0px 0px 10px;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;min-width: 0px;padding-right: 0px;width: 50%;border-collapse: collapse !important;">
															<span
																style="color: #ffffff;font-weight: bold;font-size: 11px;padding-right: 10px;font-size: 30px;">
																<?php echo h($setting['Setting']['title']); ?>
															</span>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</center>
							</td>
						</tr>
					</table>
					<table
						style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: inherit;width: 580px;margin: 0 auto;">
						<tr style="padding: 0;vertical-align: top;text-align: left;">
							<td
								style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;border-collapse: collapse !important;">
								<table
									style="border-spacing: 0;border-collapse: collapse;padding: 0px;vertical-align: top;text-align: left;width: 100%;position: relative;display: block;">
									<tr style="padding: 0;vertical-align: top;text-align: left;">
										<td
											style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 10px 20px 0px 0px;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;position: relative;padding-right: 0px;border-collapse: collapse !important;">
											<table
												style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: left;margin: 0 auto;width: 580px;">
												<tr style="padding: 0;vertical-align: top;text-align: left;">
													<td
														style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0px 0px 10px;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;border-collapse: collapse !important;">
														<p
															style="margin: 0;margin-bottom: 10px;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;padding: 0;text-align: left;line-height: 19px;font-size: 14px;">
															<?php
															 // create a new paragraph for each new line
															 $content = explode('\n', $content);
															 foreach ($content as $line) {
																echo '<p> ' . $line . '</p>';
															 }
															 ?>
														</p>
													</td>
													<td
														style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0 !important;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;visibility: hidden;width: 0px;border-collapse: collapse !important;">
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<table
									style="border-spacing: 0;border-collapse: collapse;padding: 0px;vertical-align: top;text-align: left;width: 100%;position: relative;display: block;">
									<tr style="padding: 0;vertical-align: top;text-align: left;">
										<td
											style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 10px 20px 0px 0px;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;position: relative;padding-bottom: 20px;padding-right: 0px;border-collapse: collapse !important;">
											<table
												style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: left;margin: 0 auto;width: 580px;">
												<tr style="padding: 0;vertical-align: top;text-align: left;">
													<td
														style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 10px !important;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;background: #ECF8FF;border: 1px solid #d9d9d9;border-color: #b9e5ff;border-collapse: collapse !important;">
														<p
															style="margin: 0;margin-bottom: 10px;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;padding: 0;text-align: left;line-height: 19px;font-size: 14px;">
															Stay up to date with
															<strong>
																<?php echo h($setting['Setting']['title']); ?>
															</strong>
															by heading to their
															<?php
															echo $this->Html->link(
																'main website',
																Router::url('/', true),
																array(
																	'style' => 'color: #2ba6cb;text-decoration: none;'
																)
															) . '!';
															?>
														</p>
													</td>
													<td
														style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0 !important;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;visibility: hidden;width: 0px;border-collapse: collapse !important;">
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
								<table
									style="border-spacing: 0;border-collapse: collapse;padding: 0px;vertical-align: top;text-align: left;width: 100%;position: relative;display: block;">
									<tr style="padding: 0;vertical-align: top;text-align: left;">
										<td
											style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 10px 20px 0px 0px;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;position: relative;padding-right: 0px;border-collapse: collapse !important;">
											<table
												style="border-spacing: 0;border-collapse: collapse;padding: 0;vertical-align: top;text-align: left;margin: 0 auto;width: 580px;">
												<tr style="padding: 0;vertical-align: top;text-align: left;">
													<td align="center"
														style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0px 0px 10px;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;border-collapse: collapse !important;">
														<center style="width: 100%;min-width: 580px;">
															<p
																style="text-align: center;margin: 0;margin-bottom: 10px;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;padding: 0;line-height: 19px;font-size: 14px;">
																<?php
																echo $this->Html->link(
																	'Unsubscribe',
																	Router::url(
																		array(
																			'admin' => false,
																			'controller' => 'subscriptions',
																			'action' => 'view'
																		),
																		true
																	),
																	array('style' => 'color: #2ba6cb;')
																);
																?>
																<br />
																&copy;
																<?php
																echo __(
																	'%s %s',
																	date('Y'),
																	h($setting['Setting']['copyright'])
																);
																?>
															</p>
															<p
																style="text-align: center;margin: 0;margin-bottom: 10px;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;padding: 0;line-height: 19px;font-size: 11px;">
																This message was sent using the
																<strong>
																	<?php
																	echo $this->Html->link(
																		'Robot Management System',
																		'http://wiki.ros.org/rms'
																	);
																	?>
																</strong>
															</p>
														</center>
													</td>
													<td
														style="word-break: break-word;-webkit-hyphens: auto;-moz-hyphens: auto;hyphens: auto;padding: 0 !important;vertical-align: top;text-align: left;color: #222222;font-family: &quot;Helvetica&quot;, &quot;Arial&quot;, sans-serif;font-weight: normal;margin: 0;line-height: 19px;font-size: 14px;visibility: hidden;width: 0px;border-collapse: collapse !important;">
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</center>
			</td>
		</tr>
	</table>
</body>
