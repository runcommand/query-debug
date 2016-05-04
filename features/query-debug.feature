Feature: Find bad queries in WordPress

  Scenario: --format=summary produces a human-readable summary
    Given a WP install

    When I run `wp query-debug --format=summary`
    Then STDOUT should contain:
      """
      Loading http://example.com/ executed
      """

  Scenario: --debug includes details about the main query and theme template
    Given a WP install
    And I run `wp theme install --activate p2`
    And I run `wp rewrite structure '%postname%/'`

    When I run `wp query-debug --debug`
    Then STDERR should contain:
      """
      Debug (query-debug): Main WP_Query: is_home
      """
    And STDERR should contain:
      """
      Debug (query-debug): Theme template: p2/index.php
      """

    When I run `wp post url 2`
    Then save STDOUT as {URL}

    When I run `wp query-debug --url={URL} --debug`
    Then STDERR should contain:
      """
      Debug (query-debug): Main WP_Query: is_page, is_singular
      """
    And STDERR should contain:
      """
      Debug (query-debug): Theme template: p2/page.php
      """
