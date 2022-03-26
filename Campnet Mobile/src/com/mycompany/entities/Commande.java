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
public class Commande {
    
    float id;
    float idLivreur;
    float idAdresse;
    String produit;
    float quantite;
    float total;
    String date;

    public Commande(float id, float idLivreur, float idAdresse, String produit, float quantite, float total, String date) {
        this.id = id;
        this.idLivreur = idLivreur;
        this.idAdresse = idAdresse;
        this.produit = produit;
        this.quantite = quantite;
        this.total = total;
        this.date = date;
    }

    public Commande(float idLivreur, float idAdresse, String produit, float quantite, float total, String date) {
        this.idLivreur = idLivreur;
        this.idAdresse = idAdresse;
        this.produit = produit;
        this.quantite = quantite;
        this.total = total;
        this.date = date;
    }

    public Commande() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public float getIdLivreur() {
        return idLivreur;
    }

    public void setIdLivreur(float idLivreur) {
        this.idLivreur = idLivreur;
    }

    public float getIdAdresse() {
        return idAdresse;
    }

    public void setIdAdresse(float idAdresse) {
        this.idAdresse = idAdresse;
    }

    public String getProduit() {
        return produit;
    }

    public void setProduit(String produit) {
        this.produit = produit;
    }

    public float getQuantite() {
        return quantite;
    }

    public void setQuantite(float quantite) {
        this.quantite = quantite;
    }

    public float getTotal() {
        return total;
    }

    public void setTotal(float total) {
        this.total = total;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }
    
    
    
}
