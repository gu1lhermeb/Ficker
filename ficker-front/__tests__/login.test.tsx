import React from "react";
import Home from "../src/components/Login";
import { render, screen } from "@testing-library/react";
import "@testing-library/jest-dom";

describe("Initial page", () => {
  it("should have a title", () => {
    render(<Home />);
    const title = screen.getByText("Tela inicial");
    expect(title).toBeInTheDocument();
  });
});
