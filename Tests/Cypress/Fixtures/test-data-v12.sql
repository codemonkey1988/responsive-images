TRUNCATE `pages`;
TRUNCATE `sys_file`;
TRUNCATE `sys_file_metadata`;
TRUNCATE `sys_file_reference`;
TRUNCATE `sys_file_storage`;
TRUNCATE `sys_template`;
TRUNCATE `tt_content`;

-- General setup
INSERT INTO `pages` (`uid`, `pid`, `doktype`, `is_siteroot`, `title`, `slug`, `TSconfig`)
VALUES (1, 0, 1, 1, 'Root', '/', 'TCEFORM.sys_file_reference.crop.config.cropVariants {\r\n  mobile {\r\n    title = Mobile\r\n    allowedAspectRatios {\r\n      16:9 {\r\n        title = 16:9\r\n        value = 16 / 9\r\n      }\r\n      NaN {\r\n        title = Free\r\n        value = 0.0\r\n      }\r\n    }\r\n  }\r\n  desktop {\r\n    title = Desktop\r\n    allowedAspectRatios {\r\n      4:3 {\r\n        title = 4:3\r\n        value = 4 / 3\r\n      }\r\n      NaN {\r\n        title = Free\r\n        value = 0.0\r\n      }\r\n    }\r\n  }\r\n}');

INSERT INTO `sys_file_storage` (`uid`, `pid`, `name`, `driver`, `configuration`, `is_default`, `is_browsable`, `is_public`, `is_writable`, `is_online`, `auto_extract_metadata`)
VALUES (1, 0, 'fileadmin', 'Local', '<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"basePath\">\n                    <value index=\"vDEF\">typo3conf/ext/responsive_images/Tests/Cypress/Fixtures/Storage/</value>\n                </field>\n                <field index=\"pathType\">\n                    <value index=\"vDEF\">relative</value>\n                </field>\n                <field index=\"caseSensitive\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"baseUri\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>', 1, 1, 1, 1, 1, 1);

INSERT INTO `sys_file` (`uid`, `pid`, `storage`, `type`, `identifier`, `identifier_hash`, `folder_hash`, `extension`, `mime_type`, `name`, `sha1`, `size`)
VALUES (1, 0, 1, '2', '/test.jpg', 'c775fc8846d87615cdcbfb07ffda204fc7fe0003', '42099b4af021e53fd8fd4e056c2568d7c2e3ffa8', 'jpg', 'image/jpeg', 'test.jpg', '5b30340f6adf1d2316cee2ecb276df132cd5dd30', 54846);

INSERT INTO `sys_file_metadata` (`uid`, `pid`, `file`, `width`, `height`)
VALUES (1, 0, 1, 1920, 1080);


-- Page to test Bootstrap configuration with picture tag
INSERT INTO `pages` (`uid`, `pid`, `doktype`, `is_siteroot`, `title`, `slug`, `TSconfig`)
VALUES (2, 1, 1, 0, 'Bootstrap', '/bootstrap', '');

INSERT INTO `sys_template` (`uid`, `pid`, `title`, `root`, `clear`, `include_static_file`, `constants`, `config`)
VALUES (1, 2, 'Bootstrap', 1, 1, 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:responsive_images/Configuration/TypoScript/BootstrapConfiguration', 'styles.templates.templateRootPath = EXT:responsive_images/Tests/Cypress/Fixtures/Templates/Bootstrap/', '@import "EXT:responsive_images/Tests/Cypress/Fixtures/TypoScript/page.typoscript"');

INSERT INTO `tt_content` (`uid`, `pid`, `sorting`, `CType`, `colPos`, `image`)
VALUES (1, 2, 1, 'image', 0, 1);

INSERT INTO `sys_file_reference` (`uid`, `pid`, `uid_local`, `uid_foreign`, `tablenames`, `fieldname`, `sorting_foreign`, `crop`)
VALUES (1, 2, 1, 1, 'tt_content', 'image', 1, '{\"mobile\":{\"cropArea\":{\"height\":0.5488454706927176,\"width\":1,\"x\":0,\"y\":0.15630550621669628},\"selectedRatio\":\"NaN\",\"focusArea\":null},\"desktop\":{\"cropArea\":{\"height\":1,\"width\":0.813,\"x\":0.04,\"y\":0},\"selectedRatio\":\"NaN\",\"focusArea\":null}}');


-- Page to test default configuration with picture tag
INSERT INTO `pages` (`uid`, `pid`, `doktype`, `is_siteroot`, `title`, `slug`, `TSconfig`)
VALUES (3, 1, 1, 0, 'Default', '/default', '');

INSERT INTO `sys_template` (`uid`, `pid`, `title`, `root`, `clear`, `include_static_file`, `constants`, `config`)
VALUES (2, 3, 'Default', 1, 1, 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:responsive_images/Configuration/TypoScript/DefaultConfiguration', 'styles.templates.templateRootPath = EXT:responsive_images/Tests/Cypress/Fixtures/Templates/Default/', '@import "EXT:responsive_images/Tests/Cypress/Fixtures/TypoScript/page.typoscript"');

INSERT INTO `tt_content` (`uid`, `pid`, `sorting`, `CType`, `colPos`, `image`)
VALUES (2, 3, 1, 'image', 0, 1);

INSERT INTO `sys_file_reference` (`uid`, `pid`, `uid_local`, `uid_foreign`, `tablenames`, `fieldname`, `sorting_foreign`, `crop`)
VALUES (2, 3, 1, 2, 'tt_content', 'image', 1, '{\"mobile\":{\"cropArea\":{\"height\":0.5488454706927176,\"width\":1,\"x\":0,\"y\":0.15630550621669628},\"selectedRatio\":\"NaN\",\"focusArea\":null},\"desktop\":{\"cropArea\":{\"height\":1,\"width\":0.813,\"x\":0.04,\"y\":0},\"selectedRatio\":\"NaN\",\"focusArea\":null}}');


-- Page to test default configuration with srcset image
INSERT INTO `pages` (`uid`, `pid`, `doktype`, `is_siteroot`, `title`, `slug`, `TSconfig`)
VALUES (4, 1, 1, 0, 'Srcset rendering', '/srcset-rendering', '');

INSERT INTO `sys_template` (`uid`, `pid`, `title`, `root`, `clear`, `include_static_file`, `constants`, `config`)
VALUES (3, 4, 'Srcset rendering', 1, 1, 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:responsive_images/Configuration/TypoScript/DefaultConfiguration', 'styles.templates.templateRootPath = EXT:responsive_images/Tests/Cypress/Fixtures/Templates/Srcset/', '@import "EXT:responsive_images/Tests/Cypress/Fixtures/TypoScript/page.typoscript"');

INSERT INTO `tt_content` (`uid`, `pid`, `sorting`, `CType`, `colPos`, `image`)
VALUES (3, 4, 1, 'image', 0, 1);

INSERT INTO `sys_file_reference` (`uid`, `pid`, `uid_local`, `uid_foreign`, `tablenames`, `fieldname`, `sorting_foreign`, `crop`)
VALUES (3, 4, 1, 3, 'tt_content', 'image', 1, '');
