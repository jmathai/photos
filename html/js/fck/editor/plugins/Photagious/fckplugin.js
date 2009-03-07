// Register the related commands.
var dialogPath = FCKConfig.EditorPath + 'editor/dialog/fck_photagious.html';

var ffmsDialogCmd = new FCKDialogCommand( FCKLang["DlgPhotagiousTitle"], FCKLang["DlgPhotagiousTitle"], dialogPath, 480, 470 );
FCKCommands.RegisterCommand( 'Photagious', ffmsDialogCmd ) ;

// Create the Flash toolbar button.
var oFFMSItem		= new FCKToolbarButton( 'Photagious', FCKLang["DlgPhotagiousTitle"]) ;
oFFMSItem.IconPath	= FCKConfig.EditorPath+'editor/dialog/fck_photagious/button.photagious.gif';

FCKToolbarItems.RegisterItem( 'Photagious', oFFMSItem ) ;			
// 'My_Flash' is the name used in the Toolbar config.


var PhotagiousCommand=function(){
        //create our own command, we dont want to use the FCKDialogCommand because it uses the default fck layout and not our own
};
PhotagiousCommand.prototype.Execute=function(){
}
PhotagiousCommand.GetState=function() {
        return FCK_TRISTATE_OFF; //we dont want the button to be toggled
}
PhotagiousCommand.Execute=function() {
        //open a popup window when the button is clicked
        opener = window.open(dialogPath, 'Photagious', 'width=500,height=350,scrollbars=no,scrolling=no,location=no,toolbar=no');
        opener.focus();
}
FCKCommands.RegisterCommand('Photagious', PhotagiousCommand ); //otherwise our command will not be found
var oPhotagious = new FCKToolbarButton('Photagious', 'Insert media from your Photagious account');
oPhotagious.IconPath = FCKConfig.EditorPath+'editor/dialog/fck_photagious/button.photagious.gif'; //specifies the image used in the toolbar
FCKToolbarItems.RegisterItem( 'Photagious', oPhotagious );
