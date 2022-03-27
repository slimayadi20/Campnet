/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.entities;

/**
 *
 * @author sarra
 */
public class Produit {
    
    float id;
    String nom;
    float prix;
    String description;
    String disponibilite;
    String photo;

    public Produit(float id, String nom, float prix, String description, String disponibilite, String photo) {
        this.id = id;
        this.nom = nom;
        this.prix = prix;
        this.description = description;
        this.disponibilite = disponibilite;
        this.photo = photo;
    }

    public Produit(String nom, float prix, String description, String disponibilite, String photo) {
        this.nom = nom;
        this.prix = prix;
        this.description = description;
        this.disponibilite = disponibilite;
        this.photo = photo;
    }

    public Produit() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public float getPrix() {
        return prix;
    }

    public void setPrix(float prix) {
        this.prix = prix;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getDisponibilite() {
        return disponibilite;
    }

    public void setDisponibilite(String disponibilite) {
        this.disponibilite = disponibilite;
    }

    public String getPhoto() {
        return photo;
    }

    public void setPhoto(String photo) {
        this.photo = photo;
    }
    
    
}
