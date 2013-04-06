<div id="debug-bar">
	<div id="debug-control-bar">
		<div id="debug-title" class="minor"><strong>Biscuit Debug Bar</strong><noscript> (Requires Javascript)</noscript></div>
		<div id="debug-expand-collapse"><a href="#debug-expand-collapse" id="debug-expand-collapse-button">Expand/Collapse</a></div>
		<div id="debug-toggle-fullscreen"><a href="#debug-toggle-fullscreen" id="debug-toggle-fullscreen-button">Toggle Fullscreen</a></div>
	</div>
	<div id="debug-content">
		<div id="debug-nav">
			<ul>
				<li><a href="#debug-console"    class="debug-button selected" id="button-debug-console">Console Messages</a></li>
				<li><a href="#debug-error"      class="debug-button" id="button-debug-error">Errors</a></li>
				<li><a href="#debug-db-queries" class="debug-button" id="button-debug-db-queries">Database Queries</a></li>
				<li><a href="#debug-events"     class="debug-button" id="button-debug-events">Events</a></li>
				<li><a href="#debug-var-dump"   class="debug-button" id="button-debug-var-dump">Variables</a></li>
			</ul>
		</div>
		<div id="debug-console" class="debug-content">
			<?php
			if (empty($console_log)) {
				?><h1>Console Log Empty</h1><?php
			} else {
				$counter = 0;
				foreach ($console_log as $request_marker => $messages) {
					$marker_info = Console::parse_log_marker($request_marker);
					$header_title = $DebugBar->compose_header_title($marker_info,"Message",count($messages));
					?>
			<div class="debug-header" id="debug-console-header-<?php echo $counter ?>">
				<?php echo $DebugBar->render_debug_header('console',$header_title,$counter,count($console_log)); ?>
			</div>
			<div class="debug-content-container" id="debug-console-content-container-<?php echo $counter ?>">
				<?php
					if (empty($messages)) {
						?><h1>No Messages</h1><?php
					} else {
						foreach ($messages as $message) {
							$message = str_replace(" ","&nbsp;",$message);
							?>
				<p><?php echo $message ?></p>
							<?php
						}
					}
				?>
			</div>
					<?php
					$counter++;
				}
			}
			?>
		</div>
		<div id="debug-error" class="debug-content">
			<?php
			if (empty($error_log)) {
				?><h1>Error Log Empty</h1><?php
			} else {
				$counter = 0;
				foreach ($error_log as $request_marker => $errors) {
					$marker_info = Console::parse_log_marker($request_marker);
					$header_title = $DebugBar->compose_header_title($marker_info,"Error",count($errors));
					?>
			<div class="debug-header" id="debug-error-header-<?php echo $counter ?>">
				<?php echo $DebugBar->render_debug_header('error',$header_title,$counter,count($error_log)) ?>
				<table width="100%">
					<tr>
						<th width="180">Type</th>
						<th>Message</th>
						<th width="350">File</th>
						<th width="50">Line</th>
					</tr>
				</table>
			</div>
			<div class="debug-content-container" id="debug-error-content-container-<?php echo $counter ?>">
				<?php
					if (empty($errors)) {
						?><h1>No Errors</h1><?php
					} else {
				?>
				<table width="100%">
					<?php
					foreach ($errors as $index => $error_details) {
						?>
					<tr>
						<td width="180"><?php echo $error_details[0] ?></td>
						<td><?php echo $error_details[1] ?></td>
						<td width="350"><?php echo $error_details[2] ?></td>
						<td width="50"><?php echo $error_details[3] ?></td>
					</tr>
						<?php
					}
					?>
				</table>
				<?php
					}
				?>
			</div>
					<?php
					$counter++;
				}
			}
			?>
		</div>
		<div id="debug-db-queries" class="debug-content">
			<?php
			if (empty($query_log)) {
				?><h1>Query Log Empty</h1><?php
			} else {
				$counter = 0;
				foreach ($query_log as $request_marker => $queries) {
					$marker_info = Console::parse_log_marker($request_marker);
					$header_title = $DebugBar->compose_header_title($marker_info,"Query",count($queries));
					?>
			<div class="debug-header" id="debug-query-header-<?php echo $counter ?>">
				<?php echo $DebugBar->render_debug_header('query',$header_title,$counter,count($query_log)); ?>
				<table width="100%">
					<tr>
						<th width="80">Method</th>
						<th width="350">Called By</th>
						<th>Query</th>
					</tr>
				</table>
			</div>
			<div class="debug-content-container" id="debug-query-content-container-<?php echo $counter ?>">
				<?php
					if (empty($queries)) {
						?><h1>No Queries</h1><?php
					} else {
				?>
				<table width="100%">
					<?php
					foreach ($queries as $index => $query_details) {
						?>
					<tr>
						<td width="80"><?php echo $query_details[0] ?></td>
						<td width="350"><?php echo $query_details[1] ?></td>
						<td><?php echo $query_details[2] ?></td>
					</tr>
						<?php
					}
					?>
				</table>
				<?php
					}
				?>
			</div>
					<?php
					$counter++;
				}
			}
			?>
		</div>
		<div id="debug-events" class="debug-content">
			<?php
			if (empty($event_log)) {
				?><h1>Event Log Empty</h1><?php
			} else {
				$counter = 0;
				foreach ($event_log as $request_marker => $events) {
					$marker_info = Console::parse_log_marker($request_marker);
					$header_title = $DebugBar->compose_header_title($marker_info,"Event Response",count($events));
					?>
			<div class="debug-header" id="debug-event-header-<?php echo $counter ?>">
				<?php echo $DebugBar->render_debug_header('event',$header_title,$counter,count($event_log)); ?>
				<table width="100%">
					<tr>
						<th width="240">Event Name</th>
						<th width="140">Observer</th>
						<th>Fired By</th>
					</tr>
				</table>
			</div>
			<div class="debug-content-container" id="debug-event-content-container-<?php echo $counter ?>">
				<?php
					if (empty($events)) {
						?><h1>No Events</h1><?php
					} else {
				?>
				<table width="100%">
					<?php
					foreach ($events as $index => $event_details) {
						?>
					<tr>
						<td width="240"><?php echo $event_details[0] ?></td>
						<td width="140"><?php echo $event_details[1] ?></td>
						<td><?php echo $event_details[2] ?></td>
					</tr>
						<?php
					}
					?>
				</table>
				<?php
					}
				?>
			</div>
					<?php
					$counter++;
				}
			}
			?>
		</div>
		<div id="debug-var-dump" class="debug-content">
			<?php
			if (empty($var_dump_log)) {
				?><h1>Variable Log Empty</h1><?php
			} else {
				$counter = 0;
				foreach ($var_dump_log as $request_marker => $var_dumps) {
					$marker_info = Console::parse_log_marker($request_marker);
					$header_title = $DebugBar->compose_header_title($marker_info,"Variable",count($var_dumps));
					?>
			<div class="debug-header" id="debug-var-dump-header-<?php echo $counter ?>">
				<?php echo $DebugBar->render_debug_header('var-dump',$header_title,$counter,count($var_dump_log)); ?>
				<table width="100%">
					<tr>
						<th width="140">Dumped By</th>
						<th width="160">Variable Name</th>
						<th>Contents</th>
					</tr>
				</table>
			</div>
			<div class="debug-content-container" id="debug-var-dump-content-container-<?php echo $counter ?>">
				<?php
					if (empty($var_dumps)) {
						?><h1>No Variables</h1><?php
					} else {
				?>
				<table width="100%">
					<?php
					foreach ($var_dumps as $index => $var_details) {
						?>
					<tr>
						<td width="140"><?php echo $var_details[0] ?></td>
						<td width="160"><?php echo $var_details[1] ?></td>
						<td><pre><?php htmlentities(var_export($var_details[2])); ?></pre></td>
					</tr>
						<?php
					}
					?>
				</table>
				<?php
					}
				?>
			</div>
					<?php
					$counter++;
				}
			}
			?>
		</div>
	</div>
</div>