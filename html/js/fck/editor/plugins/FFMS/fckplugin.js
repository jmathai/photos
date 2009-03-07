// Register the related commands.
var dialogPath = FCKConfig.EditorPath + 'editor/dialog/fck_ffms.php';

var ffmsDialogCmd = new FCKDialogCommand( FCKLang["DlgFFMSTitle"], FCKLang["DlgFFMSTitle"], dialogPath, 480, 470 );
FCKCommands.RegisterCommand( 'FFMS', ffmsDialogCmd ) ;

// Create the Flash toolbar button.
var oFFMSItem		= new FCKToolbarButton( 'FFMS', FCKLang["DlgFFMSTitle"]) ;
oFFMSItem.IconPath	= FCKConfig.EditorPath+'editor/dialog/fck_ffms/button.ffms.gif';

FCKToolbarItems.RegisterItem( 'FFMS', oFFMSItem ) ;			
// 'My_Flash' is the name used in the Toolbar config.


var FFMSCommand=function(){
        //create our own command, we dont want to use the FCKDialogCommand because it uses the default fck layout and not our own
};
FFMSCommand.prototype.Execute=function(){
}
FFMSCommand.GetState=function() {
        return FCK_TRISTATE_OFF; //we dont want the button to be toggled
}
FFMSCommand.Execute=function() {
        //open a popup window when the button is clicked
        window.open(dialogPath, 'FFMS', 'width=500,height=350,scrollbars=no,scrolling=no,location=no,toolbar=no');
}
FCKCommands.RegisterCommand('FFMS', FFMSCommand ); //otherwise our command will not be found
var oFFMS = new FCKToolbarButton('FFMS', 'Insert media from your Photagious account');
oFFMS.IconPath = FCKConfig.EditorPath+'editor/dialog/fck_ffms/button.ffms.gif'; //specifies the image used in the toolbar
FCKToolbarItems.RegisterItem( 'FFMS', oFFMS );
