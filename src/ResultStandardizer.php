<?php

namespace PeopleSearch;

/**
 * 
 */
class ResultStandardizer
{
  protected $affiliations = array("UNIV", "HOSP", "SOM", "APL", "MSO", "KKI", "ISIS", "SIBLEY", "SUBURBAN", "INTRASTAFF", "ADHOC");

  public function __construct()
  {

  }

  public function clean($record)
  {
    $record["standardized"] = array(
      "email" => $this->findFirstKey($record, "emaildisplay"),
      "phone" => $this->findFirstKey($record, "telephonenumber"),
      "address" => $this->findFirstKey($record, "postaladdress"),
      "associations" => $this->findAssociations($record)
    );

    return $record;
  }

  /**
   * Find the first instance of a key mashed
   * up with an affiliation and return it.
   * Useful for general contact information
   * @param  [type] $record [description]
   * @param  [type] $key    [description]
   * @return [type]         [description]
   */
  protected function findFirstKey($record, $key)
  {
    foreach ($this->affiliations as $aff) {

      $key = "jhe{$aff}{$key}";

      if (isset($record[$key])) return $record[$key];

    }

    // no match found
    return null;
  }

  protected function findAssociations($record) {

    $matches = array();

    foreach ($this->affiliations as $aff) {

      $institutionKey = "jhe{$aff}orgdn";

      if (isset($record["$institutionKey"])) {

        $titleKey = "jhe{$aff}title";
        $departmentKey = "jhe{$aff}ou";
        $unitKey = "jhe{$aff}orgunitdn";
        
        $matches[] = array(
          "title" => isset($record[$titleKey]) ? $record[$titleKey] : null,
          "department" => isset($record[$departmentKey]) ? $record[$departmentKey] : null,
          "unit" => isset($record[$unitKey]) ? $record[$unitKey] : null,
          "institution" => $record[$institutionKey]
        );

      }

    }

    if (empty($matches)) {
      print_r($result); die();
    }

    return $matches;

  }
}
