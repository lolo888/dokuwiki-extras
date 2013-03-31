<?php
/**
 * Options for the REQUIZ plugin
 *
 * @author Daniel-Constantin Mierla <miconda@gmail.com>
 */

# require quiz for authenticated users (0 - no / 1 - yes)
$conf['requizusr'] = 0;
# require quiz for registration form (0 - no / 1 - yes)
$conf['requizreg'] = 1;
# list of questions
$conf['requizset'] = array (
	array(
			"question" => "What is the capital city of France?",
			"answers" => array (
					"Berlin",
					"Paris",
					"London",
					"Madrid",
					"Tokyo"
				),
			"valid" => "Paris"
		),
	array(
			"question" => "What word starts with the fourth letter of the alphabet?",
			"answers" => array (
					"Access",
					"World",
					"Opera",
					"Direct",
					"System"
				),
			"valid" => "Direct"
		),
	array(
			"question" => "How many moons does planet Earth have?",
			"answers" => array (
					"Five",
					"Ten",
					"One",
					"Nine",
					"Four"
				),
			"valid" => "One"
		)
	);
