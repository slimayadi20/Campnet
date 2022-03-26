/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.entities;

/**
 *
 * @author karim
 */
public class Centrecamp {

    float id;
String nom_centre;
    String Description_centre;
    String img_centre;
    String lieux;
    String tlf_centre;
    String mail_centre;
    String mdps_centre;

    public Centrecamp(float id, String nom_centre, String Description_centre, String img_centre, String lieux, String tlf_centre, String mail_centre, String mdps_centre) {
        this.id = id;
        this.nom_centre = nom_centre;
        this.Description_centre = Description_centre;
        this.img_centre = img_centre;
        this.lieux = lieux;
        this.tlf_centre = tlf_centre;
        this.mail_centre = mail_centre;
        this.mdps_centre = mdps_centre;
    }

    public Centrecamp(String nom_centre, String Description_centre, String img_centre, String lieux, String tlf_centre, String mail_centre, String mdps_centre) {
        this.nom_centre = nom_centre;
        this.Description_centre = Description_centre;
        this.img_centre = img_centre;
        this.lieux = lieux;
        this.tlf_centre = tlf_centre;
        this.mail_centre = mail_centre;
        this.mdps_centre = mdps_centre;
    }

    public String getNom_centre() {
        return nom_centre;
    }

    public void setNom_centre(String nom_centre) {
        this.nom_centre = nom_centre;
    }


    public Centrecamp() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public String getDescription_centre() {
        return Description_centre;
    }

    public void setDescription_centre(String Description_centre) {
        this.Description_centre = Description_centre;
    }

    public String getImg_centre() {
        return img_centre;
    }

    public void setImg_centre(String img_centre) {
        this.img_centre = img_centre;
    }

    public String getLieux() {
        return lieux;
    }

    public void setLieux(String lieux) {
        this.lieux = lieux;
    }

    public String getTlf_centre() {
        return tlf_centre;
    }

    public void setTlf_centre(String tlf_centre) {
        this.tlf_centre = tlf_centre;
    }

    public String getMail_centre() {
        return mail_centre;
    }

    public void setMail_centre(String mail_centre) {
        this.mail_centre = mail_centre;
    }

    public String getMdps_centre() {
        return mdps_centre;
    }

    public void setMdps_centre(String mdps_centre) {
        this.mdps_centre = mdps_centre;
    }

}
