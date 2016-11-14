<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<a href="https://login.eveonline.com/oauth/authorize?response_type=code&redirect_uri=<?php echo urlencode('http://eve.denis-khodakovskiy.ru/index.php/site/callback') ?>&client_id=862b3aa0e295461f8f2fdaaf3055c3f4&scope=<?php echo urlencode(implode(' ',[
    'characterAccountRead',
    'characterAssetsRead',
    'characterBookmarksRead',
    'characterCalendarRead',
    'characterChatChannelsRead',
    'characterClonesRead',
    'characterContactsRead',
    'characterContactsWrite',
    'characterFactionalWarfareRead',
    'characterFittingsRead',
    'characterFittingsWrite',
    'characterIndustryJobsRead',
    'characterKillsRead',
    'characterLocationRead',
    'characterMailRead',
    'characterMarketOrdersRead',
    'characterMedalsRead',
    'characterNavigationWrite',
    'characterNotificationsRead',
    'characterResearchRead',
    'characterSkillsRead',
    'characterWalletRead'
])) ?>&state=uniquestate">
    <img alt="EVE SSO Login Buttons Small Black" src="https://images.contentful.com/idjq7aai9ylm/12vrPsIMBQi28QwCGOAqGk/33234da7672c6b0cdca394fc8e0b1c2b/EVE_SSO_Login_Buttons_Small_Black.png?w=195&amp;h=30">
</a>