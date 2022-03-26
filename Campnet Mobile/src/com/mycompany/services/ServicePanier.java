/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.services;

import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.ui.Dialog;
import com.codename1.ui.events.ActionListener;
import com.mycompany.entities.Panier;
import com.mycompany.utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
/**
 *
 * @author sarra
 */
public class ServicePanier {
    


    public ArrayList<Panier> Panier;
    
    public static ServicePanier instance=null;
    public boolean resultOK;
    private ConnectionRequest req;

    private ServicePanier() {
         req = new ConnectionRequest();
    }

    public static ServicePanier getInstance() {
        if (instance == null) {
            instance = new ServicePanier();
        }
        return instance;
    }
  public ArrayList<Panier> parsePanier(String jsonText){
        try {
            Panier=new ArrayList<>();
            JSONParser j = new JSONParser();// Instanciation d'un objet JSONParser permettant le parsing du résultat json

            /*
                On doit convertir notre réponse texte en CharArray à fin de
            permettre au JSONParser de la lire et la manipuler d'ou vient 
            l'utilité de new CharArrayReader(json.toCharArray())
            
            La méthode parse json retourne une MAP<String,Object> ou String est 
            la clé principale de notre résultat.
            Dans notre cas la clé principale n'est pas définie cela ne veux pas
            dire qu'elle est manquante mais plutôt gardée à la valeur par defaut
            qui est root.
            En fait c'est la clé de l'objet qui englobe la totalité des objets 
                    c'est la clé définissant le tableau de tâches.
            */
            Map<String,Object> PanierListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
            
              /* Ici on récupère l'objet contenant notre liste dans une liste 
            d'objets json List<MAP<String,Object>> ou chaque Map est une tâche.               
            
            Le format Json impose que l'objet soit définit sous forme
            de clé valeur avec la valeur elle même peut être un objet Json.
            Pour cela on utilise la structure Map comme elle est la structure la
            plus adéquate en Java pour stocker des couples Key/Value.
            
            Pour le cas d'un tableau (Json Array) contenant plusieurs objets
            sa valeur est une liste d'objets Json, donc une liste de Map
            */
            List<Map<String,Object>> list = (List<Map<String,Object>>)PanierListJson.get("root");
            
            //Parcourir la liste des tâches Json
            for(Map<String,Object> obj : list){
                //Création des tâches et récupération de leurs données
                Panier t = new Panier();
                float id = Float.parseFloat(obj.get("id").toString());
                float Quantite = Float.parseFloat(obj.get("quantite").toString());
                float Prix = Float.parseFloat(obj.get("prix").toString());
                t.setId((int)id);
                t.setNom(obj.get("nom").toString());
                t.setQuantite((int)Quantite);
                t.setPrix((int)Prix);
                
                
              
                //Ajouter la tâche extraite de la réponse Json à la liste
                Panier.add(t);
            }
            
            
        } catch (IOException ex) {
            
        }
         /*
            A ce niveau on a pu récupérer une liste des tâches à partir
        de la base de données à travers un service web
        
        */
        return Panier;
    }
    public ArrayList<Panier> getAllPaniers(){
        ArrayList<Panier> listPanier = new ArrayList<>();

        String url = Statics.BASE_URL+"/displayPanierMobile";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                Panier = parsePanier(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return Panier;
    }
 
     public void deletePanier(String id){   
        
        Dialog d = new Dialog();
            if(d.show("Supprimer Panier","Do you really want to remove this Panier?","Yes","No"))
            {             
                
                req.setUrl(Statics.BASE_URL+"/deletePanierMobile?id="+id);
               
                NetworkManager.getInstance().addToQueueAndWait(req);
                
                d.dispose();
            }
    }
 

   
}
