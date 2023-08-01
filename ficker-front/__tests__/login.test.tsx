import React from "react";
import { fireEvent, render, screen } from "@testing-library/react";
import "@testing-library/jest-dom";
import Login from "@/app/login/page";

describe("Login.tsx", () => {
  it("should show required message if the field is empty", () => {
    render(<Login />);
    const email = screen.getByLabelText("Email");
    const password = screen.getByLabelText("Senha");
    const button = screen.getByRole("button");

    fireEvent.click(button);
    expect(email).toBeRequired();
    fireEvent.change(email, { target: { value: "teste@gmail.com" } });
    fireEvent.click(button);
    expect(password).toBeRequired();
  });
});
