/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.entities;

/**
 *
 * @author karim
 */
public class Activite {
    float id;
float categorie_id;
String nom;
String photo;
String description;
String lieu;
float prix;
String statut;

    public Activite(float id, float categorie_id, String nom, String photo, String description, String lieu, float prix, String statut) {
        this.id = id;
        this.categorie_id = categorie_id;
        this.nom = nom;
        this.photo = photo;
        this.description = description;
        this.lieu = lieu;
        this.prix = prix;
        this.statut = statut;
    }

    public Activite(float categorie_id, String nom, String photo, String description, String lieu, float prix, String statut) {
        this.categorie_id = categorie_id;
        this.nom = nom;
        this.photo = photo;
        this.description = description;
        this.lieu = lieu;
        this.prix = prix;
        this.statut = statut;
    }

    public Activite() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public float getCategorie_id() {
        return categorie_id;
    }

    public void setCategorie_id(float categorie_id) {
        this.categorie_id = categorie_id;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getPhoto() {
        return photo;
    }

    public void setPhoto(String photo) {
        this.photo = photo;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getLieu() {
        return lieu;
    }

    public void setLieu(String lieu) {
        this.lieu = lieu;
    }

    public float getPrix() {
        return prix;
    }

    public void setPrix(float prix) {
        this.prix = prix;
    }

    public String getStatut() {
        return statut;
    }

    public void setStatut(String statut) {
        this.statut = statut;
    }

}
