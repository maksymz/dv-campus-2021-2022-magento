/**
 * Magento/luma - en_US
 * grunt exec:luma && grunt less:luma
 * grunt exec:luma && grunt less:luma && grunt watch
 *
 * DVCampus/luma - uk_UA
 * grunt exec:dvcampus_luma_uk_ua && grunt less:dvcampus_luma_uk_ua
 * grunt exec:dvcampus_luma_uk_ua && grunt less:dvcampus_luma_uk_ua && grunt watch:dvcampus_luma_uk_ua
 */
module.exports = {
    dvcampus_luma_uk_ua: {
        area: 'frontend',
        name: 'DVCampus/luma',
        locale: 'uk_UA',
        files: [
            'css/styles-m',
            'css/styles-l'
        ],
        dsl: 'less'
    }
};
