#!/usr/local/bin/php -q
<?php
/*
	sm.php
	part of pfSense (https://www.pfSense.org/)
	Copyright (C) 2015 ESF, LLC
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

	1. Redistributions of source code must retain the above copyright notice,
	   this list of conditions and the following disclaimer.

	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.

	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
*/
require_once("config.inc");
require_once("globals.inc");
require_once("notices.inc");

$options = getopt("s::");
$message = "";

if ($options['s'] <> "") {
	$subject = $options['s'];
}

$in = file("php://stdin");
foreach ($in as $line) {
	$line = trim($line);
	if ((substr($line, 0, 6) == "From: ") || (substr($line, 0, 6) == "Date: ") || (substr($line, 0, 4) == "To: ")) {
		continue;
	}
	if (empty($subject) && (substr($line, 0, 9) == "Subject: ")) {
		$subject = substr($line, 9);
		continue;
	}
	$message .= "$line\n";
}

if (!empty($subject)) {
	send_smtp_message($message, $subject);
} else {
	send_smtp_message($message);
}

?>