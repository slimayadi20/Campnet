/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.entities;

/**
 *
 * @author karim
 */
public class Promo {
    float id;
String Nom_promo;
float nv_prix;
String Description_promo;

    public Promo(float id, String Nom_promo, float nv_prix, String Description_promo) {
        this.id = id;
        this.Nom_promo = Nom_promo;
        this.nv_prix = nv_prix;
        this.Description_promo = Description_promo;
    }

    public Promo(String Nom_promo, float nv_prix, String Description_promo) {
        this.Nom_promo = Nom_promo;
        this.nv_prix = nv_prix;
        this.Description_promo = Description_promo;
    }

    public Promo() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public String getNom_promo() {
        return Nom_promo;
    }

    public void setNom_promo(String Nom_promo) {
        this.Nom_promo = Nom_promo;
    }

    public float getNv_prix() {
        return nv_prix;
    }

    public void setNv_prix(float nv_prix) {
        this.nv_prix = nv_prix;
    }

    public String getDescription_promo() {
        return Description_promo;
    }

    public void setDescription_promo(String Description_promo) {
        this.Description_promo = Description_promo;
    }


}
