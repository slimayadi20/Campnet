/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.entities;

/**
 *
 * @author cyrine
 */
public class Evenement {

    float id;
    float categorie_id;
    String nom;
    String photo;
    String description;
    String lieu;
    float prix;

    public Evenement(float id, String nom, String photo, String description, String lieu, float prix) {
        this.id = id;
        this.nom = nom;
        this.photo = photo;
        this.description = description;
        this.lieu = lieu;
        this.prix = prix;
    }

    public Evenement(String nom, String photo, String description, String lieu, float prix) {
        this.nom = nom;
        this.photo = photo;
        this.description = description;
        this.lieu = lieu;
        this.prix = prix;
    }

    public Evenement() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
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

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

}
