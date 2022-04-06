<?php
/**
 *
 *	Css to be inline in all template
 *
**/
?>
html,body{
	height: 100%; /* Prend toute la largeur de la fenetre */
	-webkit-text-size-adjust:100%;
	-ms-text-size-adjust:100%;
	margin:0;
	padding:0;
}
#outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
.ExternalClass{
	width : 100% !important; /* Pour HOTMAIL, force la largueur	maximum possible */
}

#backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}

body{
	overflow-x:hidden;
	outline : none;
	border: none;
	font-family:Arial, Helvetica, sans-serif;
	font-size: 15px;
	color: #231f20;
	margin:0;
	padding:0;
	background:#686868;
	-webkit-font-smoothing: antialiased; /* Allège la font sur MAC */
}

img{
	vertical-align:bottom; /* Evite d'avoir des espace non-désirés */
	border:none;
	outline:none;
	text-decoration:none;
	-ms-interpolation-mode: bicubic;
	max-width: 100%;
}

table{
	margin : 0;
	padding: 0;
	mso-table-lspace:0pt;
	mso-table-rspace:0pt;
}

td{
	vertical-align: top; /* Compense avec les images qui sont alignés vers le bottom */
	line-height: 125%;
}

a{
	display : inline;
	margin : 0;
	padding : 0;
	font-weight: 700;
	color:#231f20;
}
a:hover{text-decoration:underline!important;}
a img {border:none;}
p{
	margin:0;
	line-height:125%;
}

.emailWrapper{
	background-color: #ffffff;
}
.email-header{
	background-color: #c2c2c2;
	padding:10px 30px;
}
.email-body{
	padding:50px 0 10px;
}
.content-zone{
	padding:0 30px 20px;
}
.email-footer{
	text-align: center;
	font-size: 10px;
	padding:10px 0;
	color:#ffffff;
}

.titre{
	font-weight: 700;
	color:#000000;
	font-size: 15px;
}