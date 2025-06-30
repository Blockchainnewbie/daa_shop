<?php
    //Error Anzeige im Browser
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // --- index.php ---  
    session_start();

    // das Password mit SALT `hashen` (Ihre erweiterte Version beibehalten)
    const SALT = "ljgf542ök345gl2h62ökh452hj436";

    // die einzige Stelle für IMPORTS !! 
    require("model/user.php");  // User - Model 
    require("control/user_control.php"); // User - Controller

    require("model/artikel.php");  // Artikel - Model 
    require("control/artikel_control.php"); // Artikel - Controller
    
    require("model/kategorie.php");  // kategorie - Model 
    require("control/kategorie_control.php"); // kategorie - Controller

    require("control/system_control.php"); // system - Controller

    // Navigation einbinden - nur wenn User eingeloggt ist
    if( isset( $_SESSION['user_id'] ) == true )
    {
        // User ist eingeloggt - Navigation anzeigen
        if( isset( $_GET['action'] ) == true )
        {
            // wenn "API" NICHT in der ACTION vorkommt  
            // dann NAV laden und anzeigen  
            if( substr_count( $_GET['action'] , "API" ) < 1 )
            {
                $nav_html = file_get_contents( "view/nav.html" );
                $nav_html = str_replace( "###CONTENT###" , "" , $nav_html );
                echo $nav_html;    
            }
        }
        else
        {
            $nav_html = file_get_contents( "view/nav.html" );
            $nav_html = str_replace( "###CONTENT###" , "" , $nav_html );
            echo $nav_html;       
        }
    }
    else
    {
        // User ist NICHT eingeloggt - Login-Formular anzeigen
        if( isset( $_SESSION['user_id'] ) == false && 
            isset($_GET['action']) && 
            $_GET['action'] != "showLogin" && 
            $_GET['action'] != "checkLogin" )
        {
            // User ist NICHT eingeloggt - Login-Formular anzeigen
            header("Location: index.php?action=showLogin");
            die();
        }
        else if( !isset($_GET['action']) )
        {
            header("Location: index.php?action=showLogin");
            die();
        }
    }

    // --- Routing ---
    // wenn die Action NICHT gegeben ist .. dann .. DEFAULT 
    if( isset( $_GET['action'] ) == false )
    {
        listUser();
    }
    // Wir 'verstecken' in der action den Namen der 
    // Funktion die aufgreufen werden soll 
    else
    {
        $name_der_function = $_GET['action'];
        call_user_func( $name_der_function );
    }
?>
