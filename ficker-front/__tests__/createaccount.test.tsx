import CreateAccountPage from "@/app/createaccount/page";
import { fireEvent, render, screen } from "@testing-library/react";
import "@testing-library/jest-dom";

describe("Create Account page", () => {
  it("should be required", () => {
    render(<CreateAccountPage />);
    const name = screen.getByLabelText("Nome");
    const email = screen.getByLabelText("Email");
    const password = screen.getByLabelText("Senha");

    expect(name).toBeRequired();
    expect(email).toBeRequired();
    expect(password).toBeRequired();
  });

  it("The password should be equal", async () => {
    render(<CreateAccountPage />);

    const name = screen.getByLabelText("Nome");
    const email = screen.getByLabelText("Email");
    const password = screen.getByLabelText("Senha");
    const confirmPassword = screen.getByLabelText("Confirmar Senha");
    const button = screen.getByRole("button");

    fireEvent.change(name, { target: { value: "teste" } });
    fireEvent.change(email, { target: { value: "teste@teste.com" } });
    fireEvent.change(password, { target: { value: "1234" } });
    fireEvent.change(confirmPassword, { target: { value: "1235" } });

    fireEvent.click(button);
    const errorMessage = screen.getByText("*As senhas precisam ser iguais");

    expect(errorMessage).toBeInTheDocument();
  });
});
