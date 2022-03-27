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
import com.mycompany.entities.Centrecamp;
import com.mycompany.entities.Produit;
import com.mycompany.entities.SessionManager;
import com.mycompany.utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;
/**
 *
 * @author cyrine
 */
public class ServiceCentrecamp {
    


    public ArrayList<Centrecamp> Centrecamp;
    
    public static ServiceCentrecamp instance=null;
    public boolean resultOK;
    private ConnectionRequest req;

    private ServiceCentrecamp() {
         req = new ConnectionRequest();
    }

    public static ServiceCentrecamp getInstance() {
        if (instance == null) {
            instance = new ServiceCentrecamp();
        }
        return instance;
    }
  public ArrayList<Centrecamp> parseCentrecamp(String jsonText){
        try {
            Centrecamp=new ArrayList<>();
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
            Map<String,Object> CentrecampListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));
            
              /* Ici on récupère l'objet contenant notre liste dans une liste 
            d'objets json List<MAP<String,Object>> ou chaque Map est une tâche.               
            
            Le format Json impose que l'objet soit définit sous forme
            de clé valeur avec la valeur elle même peut être un objet Json.
            Pour cela on utilise la structure Map comme elle est la structure la
            plus adéquate en Java pour stocker des couples Key/Value.
            
            Pour le cas d'un tableau (Json Array) contenant plusieurs objets
            sa valeur est une liste d'objets Json, donc une liste de Map
            */
            List<Map<String,Object>> list = (List<Map<String,Object>>)CentrecampListJson.get("root");
            //Parcourir la liste des tâches Json
            for(Map<String,Object> obj : list){
                //Création des tâches et récupération de leurs données
                Centrecamp t = new Centrecamp();
                float id = Float.parseFloat(obj.get("id").toString());
               
               
                t.setId((int)id);
               
                t.setNom_centre(obj.get("nom_centre").toString());
                t.setDescription_centre(obj.get("Description_centre").toString());
                t.setImg_centre((obj.get("img_centre").toString()));
                t.setLieux(obj.get("lieux").toString());
                t.setTlf_centre(obj.get("tlf_centre").toString());
                t.setMail_centre(obj.get("mail_centre").toString());
                t.setMdps_centre(obj.get("mdps_centre").toString());
                
                        
                //Ajouter la tâche extraite de la réponse Json à la liste
                Centrecamp.add(t);
            }
            
            
        } catch (IOException ex) {
            
        }
         /*
            A ce niveau on a pu récupérer une liste des tâches à partir
        de la base de données à travers un service web
        
        */
        return Centrecamp;
    }
    public ArrayList<Centrecamp> getAllCentrecamps(){
        ArrayList<Centrecamp> listCentrecamp = new ArrayList<>();

        String url = Statics.BASE_URL+"/displayCentrecampsMobile";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                Centrecamp = parseCentrecamp(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return Centrecamp;
    }
 public boolean addCentrecamp(Centrecamp t) {
    
        String url = Statics.BASE_URL+"/addCentrecampMobile?nom_centre=" + t.getNom_centre()+ "&Description_centre="+t.getDescription_centre()+"&img_centre="+t.getImg_centre()+"&lieux	="+t.getLieux()+"&tlf_centre="+t.getTlf_centre()+"&mail_centre="+t.getMail_centre()+"&mdps_centre="+t.getMdps_centre(); //création de l'URL
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
     public void deletCentrecamp(float id){   
        
        Dialog d = new Dialog();
            if(d.show("Delete Centrecamp","Do you really want to remove this Centrecamp","Yes","No"))
            {             
                
                req.setUrl(Statics.BASE_URL+"/deleteCentrecampMobile?id="+id);
                //System.out.println(Statics.BASE_URL+"/deleteCentrecampMobile?id="+id);
                NetworkManager.getInstance().addToQueueAndWait(req);
                
                d.dispose();
            }

    }
 

   public boolean updateCentrecamp(Centrecamp t) {
        String url = Statics.BASE_URL+"/updateCentrecampMobile?nom_centre=" + t.getNom_centre()+ "&Description_centre="+t.getDescription_centre()+"&img_centre="+t.getImg_centre()+"&lieux	="+t.getLieux()+"&tlf_centre="+t.getTlf_centre()+"&mail_centre="+t.getMail_centre()+"&mdps_centre="+t.getMdps_centre()+"&id="+t.getId(); //création de l'URL
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
