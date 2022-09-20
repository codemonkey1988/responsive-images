TRUNCATE `pages`;
INSERT INTO `pages` (`uid`, `pid`, `doktype`, `is_siteroot`, `title`, `slug`, `TSconfig`)
VALUES (1, 0, 1, 1, 'Root', '/', 'TCEFORM.sys_file_reference.crop.config.cropVariants {\r\n  mobile {\r\n    title = Mobile\r\n    allowedAspectRatios {\r\n      16:9 {\r\n        title = 16:9\r\n        value = 16 / 9\r\n      }\r\n      NaN {\r\n        title = Free\r\n        value = 0.0\r\n      }\r\n    }\r\n  }\r\n  desktop {\r\n    title = Desktop\r\n    allowedAspectRatios {\r\n      4:3 {\r\n        title = 4:3\r\n        value = 4 / 3\r\n      }\r\n      NaN {\r\n        title = Free\r\n        value = 0.0\r\n      }\r\n    }\r\n  }\r\n}');

TRUNCATE `sys_template`;
INSERT INTO `sys_template` (`uid`, `pid`, `title`, `root`, `clear`, `include_static_file`, `constants`, `config`)
VALUES (1, 1, 'Main TypoScript Rendering', 1, 1, 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:fluid_styled_content/Configuration/TypoScript/Styling/,EXT:responsive_images/Configuration/TypoScript/BootstrapConfiguration,EXT:responsive_images/Configuration/TypoScript', 'styles.templates.templateRootPath = EXT:responsive_images/Tests/Cypress/Fixtures/Templates/', 'page = PAGE\r\npage.100 = CONTENT\r\npage.100 {\r\n    table = tt_content\r\n    select {\r\n        orderBy = sorting\r\n        where = {#colPos}=0\r\n    }\r\n}\n\nlib.contentElement.partialRootPaths.1663513618 = EXT:responsive_images/Resources/Private/Partials');

TRUNCATE `sys_file_storage`;
INSERT INTO `sys_file_storage` (`uid`, `pid`, `name`, `driver`, `configuration`, `is_default`, `is_browsable`, `is_public`, `is_writable`, `is_online`, `auto_extract_metadata`)
VALUES (1, 0, 'fileadmin', 'Local', '<?xml version=\"1.0\" encoding=\"utf-8\" standalone=\"yes\" ?>\n<T3FlexForms>\n    <data>\n        <sheet index=\"sDEF\">\n            <language index=\"lDEF\">\n                <field index=\"basePath\">\n                    <value index=\"vDEF\">typo3conf/ext/responsive_images/Tests/Cypress/Fixtures/Storage/</value>\n                </field>\n                <field index=\"pathType\">\n                    <value index=\"vDEF\">relative</value>\n                </field>\n                <field index=\"caseSensitive\">\n                    <value index=\"vDEF\">1</value>\n                </field>\n                <field index=\"baseUri\">\n                    <value index=\"vDEF\"></value>\n                </field>\n            </language>\n        </sheet>\n    </data>\n</T3FlexForms>', 1, 1, 1, 1, 1, 1);

TRUNCATE `sys_file`;
INSERT INTO `sys_file` (`uid`, `pid`, `storage`, `type`, `identifier`, `identifier_hash`, `folder_hash`, `extension`, `mime_type`, `name`, `sha1`, `size`)
VALUES (1, 0, 1, '2', '/test.jpg', 'c775fc8846d87615cdcbfb07ffda204fc7fe0003', '42099b4af021e53fd8fd4e056c2568d7c2e3ffa8', 'jpg', 'image/jpeg', 'test.jpg', '5b30340f6adf1d2316cee2ecb276df132cd5dd30', 54846);

TRUNCATE `sys_file_metadata`;
INSERT INTO `sys_file_metadata` (`uid`, `pid`, `file`, `width`, `height`)
VALUES (1, 0, 1, 1920, 1080);

TRUNCATE `tt_content`;
INSERT INTO `tt_content` (`uid`, `pid`, `sorting`, `CType`, `colPos`, `image`)
VALUES (1, 1, 1, 'image', 0, 1);

TRUNCATE `sys_file_reference`;
INSERT INTO `sys_file_reference` (`uid`, `pid`, `uid_local`, `uid_foreign`, `tablenames`, `fieldname`, `sorting_foreign`, `table_local`, `crop`)
VALUES (1, 1, 1, 1, 'tt_content', 'image', 1, 'sys_file', '{\"mobile\":{\"cropArea\":{\"height\":0.5488454706927176,\"width\":1,\"x\":0,\"y\":0.15630550621669628},\"selectedRatio\":\"NaN\",\"focusArea\":null},\"desktop\":{\"cropArea\":{\"height\":1,\"width\":0.813,\"x\":0.04,\"y\":0},\"selectedRatio\":\"NaN\",\"focusArea\":null}}');
