@javascript @mod_quiz @quizaccess @quizaccess_campla
Feature: Configuration of the CAMPLA quiz access rule
  In order to have my managers easily create CAMPLA exams
  As an admin
  I need to manage the CAMPLA credentials and URL

  @javascript
  Scenario: Configure the CAMPLA quiz access rule URL and secret
    Given I log in as "admin"
    And I navigate to "Plugins > Activity modules > Quiz > CAMPLA" in site administration
    And I set the field "CAMPLA REST API URL" to "https://campla.moodle.org"
    And I set the field "CAMPLA Application secret" to "1UmPYTe3Th1_95"
    And I set the field "CAMPLA Application ID" to "6a6f9d8f-dc8c-4fb2-ad60-ecf7acf7ef7f"
    And I press "Save changes"
    When I navigate to "Plugins > Activity modules > Quiz > CAMPLA" in site administration
    Then the field "CAMPLA REST API URL" matches value "https://campla.moodle.org"
    And the field "CAMPLA Application secret" matches value "1UmPYTe3Th1_95"
    And the field "CAMPLA Application ID" matches value "6a6f9d8f-dc8c-4fb2-ad60-ecf7acf7ef7f"
    And I log out
