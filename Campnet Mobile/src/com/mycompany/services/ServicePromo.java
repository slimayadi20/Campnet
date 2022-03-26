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
import com.mycompany.entities.Promo;
import com.mycompany.entities.SessionManager;
import com.mycompany.utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
/**
 *
 * @author sarra
 */
public class ServicePromo {
    


    public ArrayList<Promo> Promo;
    
    public static ServicePromo instance=null;
    public boolean resultOK;
    private ConnectionRequest req;

    private ServicePromo() {
         req = new ConnectionRequest();
    }

    public static ServicePromo getInstance() {
        if (instance == null) {
            instance = new ServicePromo();
        }
        return instance;
    }
  public ArrayList<Promo> parsePromo(String jsonText){
        try {
            Promo=new ArrayList<>();
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
            Map<String,Object> PromoListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
            
              /* Ici on récupère l'objet contenant notre liste dans une liste 
            d'objets json List<MAP<String,Object>> ou chaque Map est une tâche.               
            
            Le format Json impose que l'objet soit définit sous forme
            de clé valeur avec la valeur elle même peut être un objet Json.
            Pour cela on utilise la structure Map comme elle est la structure la
            plus adéquate en Java pour stocker des couples Key/Value.
            
            Pour le cas d'un tableau (Json Array) contenant plusieurs objets
            sa valeur est une liste d'objets Json, donc une liste de Map
            */
            List<Map<String,Object>> list = (List<Map<String,Object>>)PromoListJson.get("root");
            //Parcourir la liste des tâches Json
            for(Map<String,Object> obj : list){
                //Création des tâches et récupération de leurs données
                Promo t = new Promo();
                float id = Float.parseFloat(obj.get("id").toString());
                float Prix = Float.parseFloat(obj.get("nv_prix").toString());
                t.setId((int)id);
                t.setNom_promo(obj.get("Nom_promo").toString());
                t.setNv_prix((int)Prix);
                t.setDescription_promo(obj.get("Description_promo").toString());
                
                
                
               
                        
                //Ajouter la tâche extraite de la réponse Json à la liste
                Promo.add(t);
            }
            
            
        } catch (IOException ex) {
            
        }
         /*
            A ce niveau on a pu récupérer une liste des tâches à partir
        de la base de données à travers un service web
        
        */
        return Promo;
    }
    public ArrayList<Promo> getAllPromos(){
        ArrayList<Promo> listPromo = new ArrayList<>();

        String url = Statics.BASE_URL+"/displayPromoMobile";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                Promo = parsePromo(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return Promo;
    }
 public boolean addPromo(Promo t) {
    
        String url = Statics.BASE_URL+"/addPromoMobile?Nom_promo=" + t.getNom_promo()+"&nv_prix="+t.getNv_prix()+"&Description_promo="+t.getDescription_promo(); //création de l'URL
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
System.out.println(url);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
                /* une fois que nous avons terminé de l'utiliser.
                La ConnectionRequest req est unique pour tous les appels de 
                n'importe quelle méthode du Service task, donc si on ne supprime
                pas l'ActionListener il sera enregistré et donc éxécuté même si 
                la réponse reçue correspond à une autre URL(get par exemple)*/
                
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return resultOK;
    }
     public void deletPromo(float id){   
        
        Dialog d = new Dialog();
            if(d.show("Delete Promo","Do you really want to remove this Promo","Yes","No"))
            {             
                
                req.setUrl(Statics.BASE_URL+"/deletePromoMobile?id="+id);
                //System.out.println(Statics.BASE_URL+"/deletePromoMobile?id="+id);
                NetworkManager.getInstance().addToQueueAndWait(req);
                
                d.dispose();
            }
    }
 
public boolean updatePromo(Promo t) {
        String url = Statics.BASE_URL+"/updatePromoMobile?Nom_promo=" + t.getNom_promo()+"&nv_prix="+t.getNv_prix()+"&Description_promo="+t.getDescription_promo()+"&id="+t.getId(); //création de l'URL
        System.out.println(url);
        req.setUrl(url);// Insertion de l'URL de notre demande de connexion
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                resultOK = req.getResponseCode() == 200; //Code HTTP 200 OK
                req.removeResponseListener(this); //Supprimer cet actionListener
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return resultOK;    }
    
   
}
