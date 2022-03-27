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
public class Adresse {
            
    float id;
    String nom;
    String prenom;
    String adress;
    String city;
    String email;
    String tel;

    public Adresse(float id, String nom, String prenom, String adress, String city, String email, String tel) {
        this.id = id;
        this.nom = nom;
        this.prenom = prenom;
        this.adress = adress;
        this.city = city;
        this.email = email;
        this.tel = tel;
    }

    public Adresse(String nom, String prenom, String adress, String city, String email, String tel) {
        this.nom = nom;
        this.prenom = prenom;
        this.adress = adress;
        this.city = city;
        this.email = email;
        this.tel = tel;
    }

    public Adresse() {
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

    public String getPrenom() {
        return prenom;
    }

    public void setPrenom(String prenom) {
        this.prenom = prenom;
    }

    public String getAdress() {
        return adress;
    }

    public void setAdress(String adress) {
        this.adress = adress;
    }

    public String getCity() {
        return city;
    }

    public void setCity(String city) {
        this.city = city;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getTel() {
        return tel;
    }

    public void setTel(String tel) {
        this.tel = tel;
    }
    
    
}
