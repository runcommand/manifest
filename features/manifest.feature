Feature: Produce a manifest into what's going on with WordPress

  Scenario: Basic manifest
    Given a WP install

    When I run `wp core version`
    Then save STDOUT as {CORE_VERSION}

    When I run `wp manifest`
    Then STDOUT should be a table containing rows:
      | Field           | Value          |
      | core_version    | {CORE_VERSION} |
      | core_type       | standard       |
