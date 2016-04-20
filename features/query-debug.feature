Feature: Find bad queries in WordPress

  Scenario: --format=summary produces a human-readable summary
    Given a WP install

    When I run `wp query-debug --format=summary`
    Then STDOUT should contain:
      """
      Loading http://example.com/ executed
      """
