<?php

class ilMessageDefinitionElement extends ilBaseElement
{
	public function getMessageDefinitionArray($message)
	{
		$message_definition = array();

		$message_definition['name'] 		= $message['attributes']['name'];
		$message_definition['id'] 		= $message['attributes']['id'];
		$message_definition['content']	= ilBPMN2ParserUtils::extractILIASMessageDefinitionFromElement($message);

		$message_definition_array_string = " '".$message_definition['id']."' =>  array(
		'name' => '".$message_definition['name']."',
		'content' => '".$message_definition['content']['mailtext']."')";
		return $message_definition_array_string;
	}
}