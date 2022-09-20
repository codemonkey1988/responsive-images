/// <reference types="cypress" />

describe('A website user visits the homepage', () => {

  it('with 1440x900 resolution', () => {
    cy.viewport(1440, 900);

    cy.visit('/')

    cy.get('img.image-1')
      .should('be.visible')
      .and(($img) => {
        expect($img[0].naturalWidth).to.be.eq(584)
      })
      .and(($img) => {
        expect($img[0].naturalHeight).to.be.eq(405)
      })
  })

  it('with 720x500 resolution', () => {
    cy.viewport(720, 500);

    cy.visit('/')

    cy.get('img.image-1')
      .should('be.visible')
      .and(($img) => {
        expect($img[0].naturalWidth).to.be.eq(375)
      })
      .and(($img) => {
        expect($img[0].naturalHeight).to.be.eq(259)
      })
  })

  it('with 680x500 resolution', () => {
    cy.viewport(680, 500);

    cy.visit('/')

    cy.get('img.image-1')
      .should('be.visible')
      .and(($img) => {
        expect($img[0].naturalWidth).to.be.eq(375)
      })
      .and(($img) => {
        expect($img[0].naturalHeight).to.be.eq(115)
      })
  })

  it('with 420x500 resolution', () => {
    cy.viewport(420, 500);

    cy.visit('/')

    cy.get('img.image-1')
      .should('be.visible')
      .and(($img) => {
        expect($img[0].naturalWidth).to.be.eq(420)
      })
      .and(($img) => {
        expect($img[0].naturalHeight).to.be.eq(129)
      })
  })

});
