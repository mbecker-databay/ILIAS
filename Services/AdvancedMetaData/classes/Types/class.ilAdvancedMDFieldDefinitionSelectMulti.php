<?php
/* Copyright (c) 1998-2013 ILIAS open source, Extended GPL, see docs/LICENSE */

require_once "Services/AdvancedMetaData/classes/Types/class.ilAdvancedMDFieldDefinitionSelect.php";

/** 
 * AMD field type select
 * 
 * @author Jörg Lützenkirchen <luetzenkirchen@leifos.com>
 * @version $Id$
 * 
 * @ingroup ServicesAdvancedMetaData
 */
class ilAdvancedMDFieldDefinitionSelectMulti extends ilAdvancedMDFieldDefinitionSelect
{				
	const XML_SEPARATOR = "~|~";
	
	//
	// generic types
	// 
	
	public function getType()
	{
		return self::TYPE_SELECT_MULTI;
	}
	
	
	//
	// ADT
	//
	
	protected function initADTDefinition()
	{		
		$def = ilADTFactory::getInstance()->getDefinitionInstanceByType("MultiEnum");
		$def->setNumeric(false);
		
		$options = $this->getOptions();
		$def->setOptions(array_combine($options, $options));
		
		// see ilAdvancedMDValues::getActiveRecord()
		// using ilADTMultiEnumDBBridge::setFakeSingle()
		
		return $def;
	}	
	
	
	//
	// definition (NOT ADT-based)
	// 
	
	public function importDefinitionFormPostValuesNeedsConfirmation()
	{
		// handling changed values not supported yet
		return false;
	}
	
	
	// 
	// import/export
	//
	
	public function getValueForXML(ilADT $element)
	{					
		return self::XML_SEPARATOR.
			implode(self::XML_SEPARATOR, $element->getSelections()).
			self::XML_SEPARATOR;
	}
	
	public function importValueFromXML($a_cdata)
	{			
		$this->getADT()->setSelections(explode(self::XML_SEPARATOR, $a_cdata));			
	}	
}