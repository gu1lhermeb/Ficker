"use client";
import { request } from "@/service/api";
import styles from "../EnterTransaction/entertransaction.module.scss";
import { Modal, Col, Row, Select, Form, Button, Input, message } from "antd";
import { useEffect, useState } from "react";

interface CardModalProps {
  isModalOpen: boolean;
  setIsModalOpen: (value: boolean) => void;
}

interface Flag {
  id: number;
  description: string;
  created_at: Date;
  updated_at: Date;
}

export const NewCardModal = ({ isModalOpen, setIsModalOpen }: CardModalProps) => {
  const [flags, setFlags] = useState<Flag[]>([]);
  const [form] = Form.useForm();

  const handleCancel = () => {
    setIsModalOpen(false);
    form.resetFields();
  };

  const getFlags = async () => {
    try {
      const response = await request({
        method: "GET",
        endpoint: "flags",
      });
      setFlags(response.data);
    } catch (error) {
      console.log(error);
    }
  };

  const handleFinish = async () => {
    try {
      const values = await form.validateFields();
      await request({
        method: "POST",
        endpoint: "card",
        data: {
          ...values,
        },
      });
      message.success("Cartão cadastrado com sucesso!");
      handleCancel();
    } catch (errorInfo) {
      message.error("Erro ao cadastrar cartão!");
    }
  };

  useEffect(() => {
    getFlags();
    form.resetFields();
  }, []);

  return (
    <Modal
      title="Novo Cartão de Crédito"
      open={isModalOpen}
      onCancel={handleCancel}
      okButtonProps={{
        style: {
          display: "none",
        },
      }}
      cancelButtonProps={{
        style: {
          display: "none",
        },
      }}
    >
      <Form
        form={form}
        name="basic"
        data-testid="form"
        onFinish={handleFinish}
        onFinishFailed={(errorInfo) => console.log(errorInfo)}
      >
        <Col style={{ marginTop: 20 }}>
          <label>Bandeira:</label>
          <Form.Item
            name="flag_id"
            rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}
          >
            <Select
              data-testid="flag_id"
              className={styles.input}
              style={{ width: 200, height: 40 }}
              options={[
                ...flags.map((flag) => ({
                  value: flag.id,
                  label: flag.description,
                })),
              ]}
            />
          </Form.Item>
        </Col>
        <Col style={{ marginTop: 20 }}>
          <label>Descrição</label>
          <Form.Item
            name="description"
            rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}
          >
            <Input className={styles.input} style={{ width: "95%" }} data-testid="description" />
          </Form.Item>
        </Col>
        <Col style={{ marginTop: 20 }}>
          <label>Vencimento:</label>
          <Form.Item
            name="expiration"
            rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}
          >
            <Select data-testid="expiration" className={styles.input} style={{ width: 200, height: 40 }}>
              {/* Renderize as opções com os dias do mês */}
              {Array.from({ length: 31 }, (_, index) => (
                <Select.Option key={index + 1} value={index + 1}>
                  {index + 1}
                </Select.Option>
              ))}
            </Select>
          </Form.Item>
        </Col>
        <Col style={{ marginTop: 20 }}>
          <label>Melhor Dia de Compra:</label>
          <Form.Item
            name="best_day"
            rules={[{ required: true, message: "Esse campo precisa ser preenchido!" }]}
          >
            <Select data-testid="best_day" className={styles.input} style={{ width: 200, height: 40 }}>
              {/* Renderize as opções com os dias do mês */}
              {Array.from({ length: 31 }, (_, index) => (
                <Select.Option key={index + 1} value={index + 1}>
                  {index + 1}
                </Select.Option>
              ))}
            </Select>
          </Form.Item>
        </Col>
        <Row>
          <Button className={styles.modalButtonWhite} onClick={handleCancel}>
            Cancelar
          </Button>
          <Button htmlType="submit" className={styles.modalButtonPurple}>
            Adicionar
          </Button>
        </Row>
      </Form>
    </Modal>
  );
};
