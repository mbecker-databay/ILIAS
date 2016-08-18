<?php

interface ilExtractor
{
	/**
	 * @param string $event
	 * @param array  $parameters
	 *
	 * @return ilExtractedParams
	 */
	public function extract($event, $parameters);
}