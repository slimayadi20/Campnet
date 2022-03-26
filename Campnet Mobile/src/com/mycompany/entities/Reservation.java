/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */
package com.mycompany.entities;

/**
 *
 * @author cyrine
 */
public class Reservation {

    float id;
    float nbr_prs;
    String date;
    String date_r;
    float Evenement_id;

    public Reservation(float id, float nbr_prs, String date, String date_r, float Evenement_id) {
        this.id = id;
        this.nbr_prs = nbr_prs;
        this.date = date;
        this.date_r = date_r;
        this.Evenement_id = Evenement_id;
    }

    public Reservation(float nbr_prs, String date, String date_r, float Evenement_id) {
        this.nbr_prs = nbr_prs;
        this.date = date;
        this.date_r = date_r;
        this.Evenement_id = Evenement_id;
    }

    public Reservation() {
    }

    public float getId() {
        return id;
    }

    public void setId(float id) {
        this.id = id;
    }

    public float getNbr_prs() {
        return nbr_prs;
    }

    public void setNbr_prs(float nbr_prs) {
        this.nbr_prs = nbr_prs;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public String getDate_r() {
        return date_r;
    }

    public void setDate_r(String date_r) {
        this.date_r = date_r;
    }

    public float getEvenement_id() {
        return Evenement_id;
    }

    public void setEvenement_id(float Evenement_id) {
        this.Evenement_id = Evenement_id;
    }

}
