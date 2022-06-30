<?php
$destinataire = 'Email de destinataire exemple 
 aaa@gmail.com';
    $copie = 'oui';
    // si cette page est index.php?page=contact alors mettez index.php?page=contact
    // sinon, laissez vide
    $form_action = '';
    // Messages de confirmation du mail
    $Message_envoye = "Votre Message nous est bien parvenu !";
    $Message_non_envoye = "L'envoi du mail a échoué, veuillez réessayer SVP.";
    // Message d'erreur du formulaire
    $Message_formulaire_invalide = "Vérifiez que tous les champs soient bien remplis et que l'Email soit sans erreur.";
 
    function Rec($text)
    {
        $text = trim($text); // delete white spaces after & before text
        if (1 === get_magic_quotes_gpc())
        {
            $stripslashes = create_function('$txt', 'return stripslashes($txt);');
        }
        else
        {
            $stripslashes = create_function('$txt', 'return $txt;');
        }
        $text = $stripslashes($text);
        $text = htmlspecialchars($text, ENT_QUOTES); // converts to string with " and ' as well
        $text = nl2br($text);
        return $text;
    };
//Vérification la syntaxe d'un Email
    function IsEmail($Email)
    {
        $pattern = "^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,7}$";
        return (eregi($pattern,$Email)) ? true : false;
    };
 
    $err_formulaire = false; // sert pour remplir le formulaire en cas d'erreur si besoin
 
    // si formulaire envoyé, on récupère tous les champs. Sinon, on initialise les variables.
    $nom     = (isset($_POST['nom']))     ? Rec($_POST['nom'])     : '';
    $Email   = (isset($_POST['Email']))   ? Rec($_POST['Email'])   : '';
    $Objet   = (isset($_POST['Objet']))   ? Rec($_POST['Objet'])   : '';
    $Message = (isset($_POST['Message'])) ? Rec($_POST['Message']) : '';
    if (isset($_POST['envoi']))
    {
        // On va vérifier les variables et l'Email ...
        $Email = (IsEmail($Email)) ? $Email : ''; // soit l'Email est vide si erroné, soit il vaut l'Email entré
        $err_formulaire = (IsEmail($Email)) ? false : true;
 
        if (($nom != '') && ($Email != '') && ($Objet != '') && ($Message != ''))
        {
            // les 4 variables sont remplies, on génère puis envoie le mail
            $headers = 'From: '.$nom.' <'.$Email.'>' . "\r\n";
            // envoyer une copie au visiteur ?
            if ($copie == 'oui')
            {
                $cible = $destinataire.','.$Email;
            }
            else
            {
                $cible = $destinataire;
            };
            // Remplacement de certains caractères spéciaux
            $Message = html_entity_decode($Message);
            $Message = str_replace('',"'",$Message);
            $Message = str_replace('',"'",$Message);
            $Message = str_replace('
','',$Message);
            $Message = str_replace('
','',$Message);
            // Envoi du mail
            if (mail($cible, $Objet, $Message, $headers))
            {
                echo '
 
'.$Message_envoye.'
'."\n";
            }
            else
            {
                echo '
 
'.$Message_non_envoye.'
'."\n";
            };
        }
        else
        {
            // une des 3 variables (ou plus) est vide ...
            echo '
 
'.$Message_formulaire_invalide.' Retour au formulaire
'."\n";
            $err_formulaire = true;
        };
    }; // fin du if (!isset($_POST['envoi']))
 
    if (($err_formulaire) || (!isset($_POST['envoi'])))
    {
        // afficher le formulaire
        echo '<form id="contact" method="post" action="'.$form_action.'">'."\n";
        echo '  <fieldset><legend>......</legend>'."\n";
        echo '      <p>'."\n";
        echo '          <label for="nom">Nom :</label>'."\n";
        echo '          <input type="text" id="nom" name="nom" value="'.stripslashes($nom).'" tabindex="1" />'."\n";
        echo '      </p>'."\n";
        echo '      <p>'."\n";
        echo '          <label for="Email">Email :</label>'."\n";
        echo '          <input type="text" id="Email" name="Email" value="'.stripslashes($Email).'" tabindex="2" />'."\n";
        echo '      </p>'."\n";
        echo '      <p>'."\n";
        echo '          <label for="Objet">Objet :</label>'."\n";
        echo '          <input type="text" id="Objet" name="Objet" value="'.stripslashes($Objet).'" tabindex="3" />'."\n";
        echo '      </p>'."\n";
        echo '      <p>'."\n";
        echo '          <label for="Message">Message :</label>'."\n";
        echo '      <textarea id="Message" name="Message" tabindex="4" cols="30" rows="8">'.stripslashes($Message).'</textarea>'."\n";
        echo '      </p>'."\n";
        echo '  </fieldset>'."\n";
 
        echo '  <div style="text-align:center;"><input type="submit" name="envoi" value="Envoyer" /></div>'."\n";
        echo '</form>'."\n";
 
    };
?>
